import axios from 'axios';

const SPOONACULAR_API_KEY = '6e16f00bf7cb4232834000bcc58bb787';

export const searchRecipeByName = async (query) => {
  const trySearch = async (q) => {
    const searchRes = await axios.get(
      'https://api.spoonacular.com/recipes/complexSearch',
      {
        params: {
          apiKey: SPOONACULAR_API_KEY,
          query: q,
          number: 1,
        },
      }
    );

    return searchRes.data.results?.[0] || null;
  };

  try {
    // Step 1: 通常の検索
    let recipe = await trySearch(query);

    // Step 2: ヒットしなければ、最後の単語でフォールバック検索
    if (!recipe) {
      const fallbackKeyword = query.split(' ').slice(-1)[0];
      console.log('🔁 フォールバック検索中:', fallbackKeyword);
      recipe = await trySearch(fallbackKeyword);
    }

    if (!recipe) throw new Error('レシピが見つかりません');

    // Step 3: 詳細取得
    const detailRes = await axios.get(
      `https://api.spoonacular.com/recipes/${recipe.id}/information`,
      {
        params: {
          apiKey: SPOONACULAR_API_KEY,
          includeNutrition: true,
        },
      }
    );

    const detail = detailRes.data;

    const get = (name) =>
      detail.nutrition?.nutrients?.find((n) => n.name === name) || {};

    return {
      id: detail.id,
      name: detail.title,
      image: detail.image,
      summary: detail.summary?.replace(/<[^>]+>/g, ''),
      instructions: detail.analyzedInstructions?.[0]?.steps
        ?.map((step) => step.step)
        .join('\n') || '手順情報がありません。',
      ingredients: detail.extendedIngredients?.map(i => i.original) || [],
      nutrition: {
        calories: Math.round(get('Calories')?.amount || 0),
        protein: Math.round(get('Protein')?.amount || 0),
        fat: Math.round(get('Fat')?.amount || 0),
      },
    };

  } catch (error) {
    console.error('Spoonacular API エラー:', error);
    return null;
  }
};
