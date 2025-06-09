import axios from 'axios';
import { OPENAI_API_KEY } from '@env'; // ← ここで読み込む

const GPT_ENDPOINT = 'https://api.openai.com/v1/chat/completions';

// ① ChatGPTで英語の料理名を生成
export const getRecipeKeywordFromGPT = async (userInputText) => {
  const prompt = `
ユーザーが次のような気分・体調を入力しました：
"${userInputText}"

この内容に合った料理を1つだけ、Spoonacularに登録されていそうな英語の料理名（例：Grilled Chicken Salad）で提案してください。
返答は料理名のみ。他の説明は不要です。
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

  return res.data.choices[0].message.content.trim();
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
