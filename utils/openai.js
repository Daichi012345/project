import axios from 'axios';
import { OPENAI_API_KEY } from '@env';
import { getSuggestionCache, saveSuggestionCache } from './suggestionCache';

const GPT_ENDPOINT = 'https://api.openai.com/v1/chat/completions';
const GPT_MODEL = 'gpt-4o';

/**
  気分・体調に応じたジャンル分類
 **/
export const classifyMoodToGenre = async (userInputText) => {
  const prompt = `
あなたは食事提案AIです。
以下の気分・体調に対して、以下2つを日本語で出力してください：

1. 料理ジャンル（例：辛いもの、さっぱり、エネルギー系、濃い味、ヘルシー など）
2. そのジャンルを選んだ理由（簡潔に1文）

出力形式：
ジャンル: ○○○
理由: ○○○○○○○○○○○○

気分・体調: "${userInputText}"
`;

  const res = await axios.post(
    GPT_ENDPOINT,
    {
      model: GPT_MODEL,
      messages: [{ role: 'user', content: prompt }],
      temperature: 0.5,
    },
    {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${OPENAI_API_KEY}`,
      },
    }
  );

  const text = res.data.choices[0].message.content.trim();
  const genreMatch = text.match(/ジャンル[:：]\s*(.+)/);
  const reasonMatch = text.match(/理由[:：]\s*(.+)/);

  return {
    genre: genreMatch ? genreMatch[1] : '不明',
    reason: reasonMatch ? reasonMatch[1] : '理由が取得できませんでした',
  };
};

/**
 料理名提案（ジャンル・気分・アレルギー考慮）
 **/
export const getRecipeKeywordFromGPT = async (genreText, userInputText, allergyList = []) => {
  const allergyText = allergyList.length > 0
    ? `※以下の食材は絶対に含まないでください：${allergyList.join(', ')}`
    : '';

  const prompt = `
あなたは料理提案AIです。
次の条件を考慮し、Spoonacularに登録されていそうな主食または主菜レベルの料理名（英語のみ、例：Grilled Chicken Salad、Beef Stir-Fry）を1つだけ提案してください。
創作風・ユニークすぎる名前は禁止。スイーツ・デザート・軽食・飲み物は禁止。

気分・体調: ${userInputText}
希望ジャンル: ${genreText}
${allergyText}

料理名のみ返答してください。他の説明や記号は禁止です。
`;

  const res = await axios.post(
    GPT_ENDPOINT,
    {
      model: GPT_MODEL,
      messages: [{ role: 'user', content: prompt }],
      temperature: 0.5,
    },
    {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${OPENAI_API_KEY}`,
      },
    }
  );

  return res.data.choices[0].message.content.trim();
};

/**
 英語の料理名を日本語に翻訳
 **/
export const translateRecipeName = async (englishName) => {
  const prompt = `"${englishName}" を自然な日本語の料理名にしてください。料理名のみ返答。`;

  const res = await axios.post(
    GPT_ENDPOINT,
    {
      model: GPT_MODEL,
      messages: [{ role: 'user', content: prompt }],
      temperature: 0.3,
    },
    {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${OPENAI_API_KEY}`,
      },
    }
  );

  return res.data.choices[0].message.content.trim();
};

/**
 任意の文章を日本語に翻訳
 **/
export const translateText = async (text) => {
  const prompt = `次の英語の文章を自然な日本語に翻訳してください：\n\n${text}`;

  const res = await axios.post(
    GPT_ENDPOINT,
    {
      model: GPT_MODEL,
      messages: [{ role: 'user', content: prompt }],
      temperature: 0.5,
    },
    {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${OPENAI_API_KEY}`,
      },
    }
  );

  return res.data.choices[0].message.content.trim();
};

/**
  提案ハンドリング（検索フロー込み・キャッシュ対応）
 **/
export const handleSubmit = async (userInput, user, navigation, setLoading, searchRecipeByName, Alert) => {
  if (!userInput.trim()) {
    Alert.alert('入力エラー', '気分や体調を入力してください');
    return;
  }

  setLoading(true);

  try {
    const allergyList = user?.allergy?.split(',').map(a => a.trim()) || [];
    const cacheKey = `${userInput.trim()}___${allergyList.join(',')}`;
    const cache = await getSuggestionCache();

    if (cache[cacheKey]) {
      console.log('✅ キャッシュから即時提案');
      navigation.navigate('MealSuggestionScreen', { meal: cache[cacheKey] });
      setLoading(false);
      return;
    }

    console.log('🚀 キャッシュなし → 通常処理開始');
    const { genre, reason } = await classifyMoodToGenre(userInput);
    console.log('🎨 分類ジャンル:', genre);

    let keyword = await getRecipeKeywordFromGPT(genre, userInput, allergyList);
    console.log('🍽️ GPT生成料理名:', keyword);

    let recipe = await searchRecipeByName(keyword, allergyList);
    if (!recipe) {
      console.log('🔄 再検索（気分・体調のみで再提案）');
      keyword = await getRecipeKeywordFromGPT('', userInput, allergyList);
      console.log('🍽️ 再提案料理名:', keyword);
      recipe = await searchRecipeByName(keyword, allergyList);

      if (!recipe) {
        Alert.alert('レシピ未発見', '条件を変えて再試行してください。');
        return;
      }
    }

    const jpName = await translateRecipeName(recipe.name);
    console.log('🇯🇵 日本語訳:', jpName);

    const meal = {
      ...recipe,
      name: jpName,
      mood: genre,
      reason: reason,
    };

    cache[cacheKey] = meal;
    await saveSuggestionCache(cache);
    console.log('✅ キャッシュ保存完了');

    navigation.navigate('MealSuggestionScreen', { meal });

  } catch (err) {
    console.error('提案エラー:', err);
    Alert.alert('エラー', '提案の取得に失敗しました');
  } finally {
    setLoading(false);
  }
};
