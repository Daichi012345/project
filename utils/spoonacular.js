import axios from 'axios';

const SPOONACULAR_API_KEY = '6e16f00bf7cb4232834000bcc58bb787';

export const searchRecipeByName = async (query) => {
  try {
    // Step 1: Get ID from complexSearch
    const searchRes = await axios.get(
      'https://api.spoonacular.com/recipes/complexSearch',
      {
        params: {
          apiKey: SPOONACULAR_API_KEY,
          query,
          number: 1,
        },
      }
    );

    const recipe = searchRes.data.results[0];
    if (!recipe) throw new Error('レシピが見つかりません');

    // Step 2: Get full details by ID
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
