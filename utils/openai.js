import axios from 'axios';
import { OPENAI_API_KEY } from '@env'; // ← ここで読み込む

const GPT_ENDPOINT = 'https://api.openai.com/v1/chat/completions';


export const getRecipeKeywordFromGPT = async (userInputText, allergyList = []) => {
  const allergyText = allergyList.length > 0
    ? `※以下の食材は絶対に含まないでください（これらが含まれる料理は無効です）：${allergyList.join(', ')}

また、可能であれば以下の代替食材を使った料理を提案してください：

${allergyList.map(allergen => {
      if (allergen.includes('乳') || allergen.includes('牛乳')) return '牛乳 → 豆乳やアーモンドミルク';
      if (allergen.includes('卵')) return '卵 → 豆腐やアクアファバ';
      if (allergen.includes('小麦')) return '小麦 → 米粉やオートミール';
      if (allergen.includes('ナッツ')) return 'ナッツ → ひまわりの種やかぼちゃの種';
      if (allergen.includes('いちご')) return 'いちご → 他の果物（例：バナナ、りんご）';
      return `${allergen} の代わりに安全な食材`;
    }).join('\n')}`
    : '';

  const prompt = `
ユーザーが次のような気分・体調を入力しました：
"${userInputText}"

この内容に合った **主食または主菜としてふさわしい料理** を1つだけ、Spoonacularに登録されていそうな英語の料理名（例：Grilled Chicken Salad）で提案してください。

${allergyText}

※以下のようなジャンルは除外してください：お菓子、スイーツ、デザート、飲み物、軽食。

返答は、アレルギー食材を一切含まない「主食・主菜レベルの料理名」のみとしてください。
料理名のみ。他の説明や記号は不要です。
`;

  const res = await axios.post(
    'https://api.openai.com/v1/chat/completions',
    {
      model: 'gpt-3.5-turbo',
      messages: [{ role: 'user', content: prompt }],
      temperature: 0.7,
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

// ④ 気分テキストをジャンル分類（例：辛いもの、さっぱり系、エネルギー系）
export const classifyMoodToGenre = async (userInputText) => {
  const prompt = `
あなたは食事提案AIです。
以下の気分・体調に対して、以下2つを日本語で出力してください：

1. 料理ジャンル（例：辛いもの、甘いもの、さっぱり、エネルギー系、濃い味、ヘルシー など）
2. そのジャンルを選んだ理由（簡潔に1文）

出力形式は次の通りです：
ジャンル: ○○○
理由: ○○○○○○○○○○○○

気分・体調: "${userInputText}"
`;

  const res = await axios.post(
    GPT_ENDPOINT,
    {
      model: 'gpt-3.5-turbo',
      messages: [{ role: 'user', content: prompt }],
      temperature: 0.7,
    },
    {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${OPENAI_API_KEY}`,
      },
    }
  );

  const text = res.data.choices[0].message.content.trim();

  // 正規表現でパース
  const genreMatch = text.match(/ジャンル[:：]\s*(.+)/);
  const reasonMatch = text.match(/理由[:：]\s*(.+)/);

  return {
    genre: genreMatch ? genreMatch[1] : '不明',
    reason: reasonMatch ? reasonMatch[1] : '理由が取得できませんでした',
  };
};






// ② 英語の料理名を日本語に翻訳
export const translateRecipeName = async (englishName) => {
  const prompt = `
次の料理名を自然な日本語に翻訳してください：
"${englishName}"

※料理名のみを返してください。他の説明文や記号は不要です。
`;

  const res = await axios.post(
    GPT_ENDPOINT,
    {
      model: 'gpt-3.5-turbo',
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

// ③ 任意の文章を翻訳
export const translateText = async (text) => {
  const prompt = `次の英語の文章を自然な日本語に翻訳してください：\n\n${text}`;

  const res = await axios.post(
    GPT_ENDPOINT,
    {
      model: 'gpt-3.5-turbo',
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
