import React, { useState, useContext } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert
} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { UserContext } from '../contexts/UserContext';

import { getRecipeKeywordFromGPT, translateRecipeName } from '../utils/openai';
import { searchRecipeByName } from '../utils/spoonacular';
import { classifyMoodToGenre } from '../utils/openai';


export default function MoodInputScreen() {
  const [userInput, setUserInput] = useState('');
  const [loading, setLoading] = useState(false);
  const navigation = useNavigation();
  const { user } = useContext(UserContext); // ← ここ変更！

  const handleSubmit = async () => {
    if (!userInput.trim()) {
      Alert.alert('入力エラー', '気分や体調を入力してください');
      return;
    }

    setLoading(true);

    try {
      const allergyList = user?.allergy?.split(',').map(a => a.trim()) || [];
      console.log('アレルギー:', allergyList);

      // 🔄 修正ここから！
      const { genre, reason } = await classifyMoodToGenre(userInput);
      console.log('分類されたジャンル:', genre);
      console.log('理由:', reason);

      const keyword = await getRecipeKeywordFromGPT(genre, allergyList);
      console.log('GPT生成料理名:', keyword);

      const recipe = await searchRecipeByName(keyword);
      if (!recipe) {
        Alert.alert('レシピが見つかりません', '該当する料理が見つかりませんでした。');
        return;
      }

      const jpName = await translateRecipeName(recipe.name);

      const recipeWithJP = {
        ...recipe,
        name: jpName,
      };

      navigation.navigate('MealSuggestionScreen', {
        meal: recipeWithJP,
        genre,    // 👈 MealSuggestionScreenに渡す
        reason,   // 👈 MealSuggestionScreenに渡す
      });
    } catch (err) {
      console.error('提案エラー:', err);
      Alert.alert('エラー', '食事提案の取得に失敗しました');
    } finally {
      setLoading(false);
    }
  };




  return (
    <View style={styles.container}>
      <Text style={styles.label}>今の気分や体調を自由に入力してください</Text>
      <TextInput
        style={styles.input}
        placeholder="例：疲れていてあっさりしたものが食べたい"
        value={userInput}
        onChangeText={setUserInput}
        multiline
      />
      <TouchableOpacity
        style={styles.button}
        onPress={handleSubmit}
        disabled={loading}
      >
        {loading ? (
          <ActivityIndicator color="#fff" />
        ) : (
          <Text style={styles.buttonText}>食事を提案してもらう</Text>
        )}
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 24, backgroundColor: '#fff' },
  label: { fontSize: 18, marginBottom: 12 },
  input: {
    borderColor: '#ccc',
    borderWidth: 1,
    borderRadius: 8,
    padding: 12,
    height: 100,
    textAlignVertical: 'top',
    marginBottom: 20,
    fontSize: 16
  },
  button: {
    backgroundColor: '#4CAF50',
    padding: 16,
    borderRadius: 10,
    alignItems: 'center'
  },
  buttonText: { color: '#fff', fontSize: 16, fontWeight: 'bold' }
});
