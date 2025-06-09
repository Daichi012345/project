import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  KeyboardAvoidingView,
  Platform,
  Keyboard,
  TouchableWithoutFeedback,
} from 'react-native';

const METS = {
  ウォーキング: 3.5,
  ジョギング: 7.0,
  サイクリング: 6.8,
  筋トレ: 5.0,
};

export default function ExerciseInputScreen() {
  const [exercise, setExercise] = useState('');
  const [minutes, setMinutes] = useState('');
  const [calories, setCalories] = useState(null);

  const handleCalculate = () => {
    Keyboard.dismiss(); // 🟢 キーボードを閉じる
    const met = METS[exercise];
    const min = parseFloat(minutes);

    if (!met || isNaN(min)) {
      setCalories(null);
      alert('正しい運動名（例: ウォーキング）と時間を入力してください');
      return;
    }

    const weight = 60; // 仮に体重60kgとして計算
    const kcal = met * weight * (min / 60);
    setCalories(kcal.toFixed(1));
  };

  return (
    <TouchableWithoutFeedback onPress={Keyboard.dismiss}>
      <KeyboardAvoidingView
        style={styles.container}
        behavior={Platform.OS === 'ios' ? 'padding' : undefined}
      >
        <Text style={styles.title}>運動による消費カロリー</Text>

        <TextInput
          style={styles.input}
          placeholder="運動内容（例: ウォーキング）"
          value={exercise}
          onChangeText={setExercise}
        />

        <TextInput
          style={styles.input}
          placeholder="運動時間（分）"
          value={minutes}
          onChangeText={setMinutes}
          keyboardType="numeric"
        />

        <TouchableOpacity style={styles.button} onPress={handleCalculate}>
          <Text style={styles.buttonText}>カロリーを計算</Text>
        </TouchableOpacity>

        {calories && (
          <Text style={styles.result}>
            消費カロリー: {calories} kcal（60kgで推定）
          </Text>
        )}
      </KeyboardAvoidingView>
    </TouchableWithoutFeedback>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 24, backgroundColor: '#fff', justifyContent: 'center' },
  title: { fontSize: 22, fontWeight: 'bold', marginBottom: 20, textAlign: 'center' },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 8,
    padding: 12,
    marginBottom: 16,
  },
  button: {
    backgroundColor: '#4CAF50',
    padding: 14,
    borderRadius: 10,
    alignItems: 'center',
    marginBottom: 16,
  },
  buttonText: { color: '#fff', fontWeight: 'bold', fontSize: 16 },
  result: { fontSize: 18, textAlign: 'center', marginTop: 10, color: '#333' },
});
