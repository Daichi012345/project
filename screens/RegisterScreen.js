import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Alert,
  ScrollView,
  KeyboardAvoidingView,
  Platform,
  Keyboard,
  TouchableWithoutFeedback,
} from 'react-native';
import { API_BASE_URL } from '@env';


const RegisterScreen = ({ navigation }) => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [age, setAge] = useState('');
  const [gender, setGender] = useState('');
  const [allergy, setAllergy] = useState('');

  const handleRegister = async () => {
    if (!email || !password || !name) {
      Alert.alert('入力エラー', '名前・メール・パスワードを入力してください');
      return;
    }

    try {
      const response = await fetch(`${API_BASE_URL}/api/register`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: name.trim(),
          email: email.trim(),
          password: password.trim(),
          age: Number(age),
          gender: gender.trim(),
          allergy: allergy.trim(),
        }),
      });

      const data = await response.json();

      if (response.ok) {
        Alert.alert('登録完了', 'ログインしてください');
        navigation.navigate('Login');
      } else {
        Alert.alert('登録失敗', data.message || 'サーバーエラー');
      }
    } catch (error) {
      console.error('登録エラー:', error);
      Alert.alert('通信エラー', 'もう一度お試しください');
    }
  };

  return (
    <KeyboardAvoidingView
      style={{ flex: 1 }}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <TouchableWithoutFeedback onPress={Keyboard.dismiss}>
        <ScrollView contentContainerStyle={styles.container}>
          <Text style={styles.title}>ユーザー登録</Text>

          <TextInput
            placeholder="名前"
            style={styles.input}
            value={name}
            onChangeText={setName}
          />
          <TextInput
            placeholder="メールアドレス"
            style={styles.input}
            value={email}
            onChangeText={setEmail}
            keyboardType="email-address"
            autoCapitalize="none"
          />
          <TextInput
            placeholder="パスワード"
            style={styles.input}
            value={password}
            onChangeText={setPassword}
            secureTextEntry
          />
          <TextInput
            placeholder="年齢"
            style={styles.input}
            value={age}
            onChangeText={setAge}
            keyboardType="numeric"
          />
          <TextInput
            placeholder="性別（例：男性 / 女性 / その他）"
            style={styles.input}
            value={gender}
            onChangeText={setGender}
          />
          <TextInput
            placeholder="アレルギー（例：卵, 乳）"
            style={styles.input}
            value={allergy}
            onChangeText={setAllergy}
          />

          <TouchableOpacity style={styles.button} onPress={handleRegister}>
            <Text style={styles.buttonText}>登録する</Text>
          </TouchableOpacity>

          <TouchableOpacity onPress={() => navigation.navigate('Login')}>
            <Text style={styles.link}>すでにアカウントをお持ちですか？ログイン</Text>
          </TouchableOpacity>
        </ScrollView>
      </TouchableWithoutFeedback>
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 20,
    flexGrow: 1,
    backgroundColor: '#fff',
    justifyContent: 'center',
  },
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    marginBottom: 24,
    textAlign: 'center',
    color: '#333',
  },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 12,
    padding: 14,
    marginBottom: 16,
    fontSize: 16,
    backgroundColor: '#f9f9f9',
  },
  button: {
    backgroundColor: '#4CAF50',
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 17,
  },
  link: {
    color: '#007AFF',
    marginTop: 18,
    textAlign: 'center',
    fontSize: 15,
  },
});

export default RegisterScreen;
