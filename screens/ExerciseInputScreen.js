import React, { useState } from 'react';
import {
  View, Text, TextInput, TouchableOpacity, StyleSheet,
  KeyboardAvoidingView, Platform, Keyboard, TouchableWithoutFeedback, Alert
} from 'react-native';
import { Picker } from '@react-native-picker/picker'; // 必要：npm install @react-native-picker/picker

const METS = {
  ウォーキング: 3.5,
  ジョギング: 7.0,
  サイクリング: 6.8,
  筋トレ: 5.0,
  ランニング: 8.3,
  ヨガ: 2.5,
  階段昇降: 4.0,
};

export default function ExerciseInputScreen() {
  const [exercise, setExercise] = useState('ウォーキング');
  const [minutes, setMinutes] = useState('');
  const [weight, setWeight] = useState('');
  const [calories, setCalories] = useState(null);

  const handleCalculate = () => {
    Keyboard.dismiss();

    const met = METS[exercise];
    const min = parseFloat(minutes);
    const w = parseFloat(weight);

    if (isNaN(min) || min <= 0 || isNaN(w) || w <= 0) {
      Alert.alert('入力エラー', '運動時間と体重を正しく入力してください（数値）');
      return;
    }

    const kcal = met * w * (min / 60);
    setCalories(kcal.toFixed(1));
  };

  return (
    <TouchableWithoutFeedback onPress={Keyboard.dismiss}>
      <KeyboardAvoidingView style={styles.container} behavior={Platform.OS === 'ios' ? 'padding' : undefined}>
        <Text style={styles.title}>運動による消費カロリー</Text>

        <Text style={styles.label}>運動の種類</Text>
        <View style={styles.pickerWrapper}>
          <Picker
            selectedValue={exercise}
            onValueChange={(itemValue) => setExercise(itemValue)}
          >
            {Object.keys(METS).map((key) => (
              <Picker.Item label={key} value={key} key={key} />
            ))}
          </Picker>
        </View>

        <Text style={styles.label}>運動時間（分）</Text>
        <TextInput
          style={styles.input}
          placeholder="例：30"
          value={minutes}
          onChangeText={setMinutes}
          keyboardType="numeric"
        />

        <Text style={styles.label}>体重（kg）</Text>
        <TextInput
          style={styles.input}
          placeholder="例：60"
          value={weight}
          onChangeText={setWeight}
          keyboardType="numeric"
        />

        <TouchableOpacity style={styles.button} onPress={handleCalculate}>
          <Text style={styles.buttonText}>カロリーを計算</Text>
        </TouchableOpacity>

        {calories && (
          <Text style={styles.result}>
            消費カロリー: {calories} kcal
          </Text>
        )}
      </KeyboardAvoidingView>
    </TouchableWithoutFeedback>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 24, backgroundColor: '#fff', justifyContent: 'center' },
  title: { fontSize: 22, fontWeight: 'bold', marginBottom: 20, textAlign: 'center' },
  label: { fontSize: 16, marginBottom: 6, marginTop: 10 },
  input: {
    borderWidth: 1, borderColor: '#ccc', borderRadius: 8, padding: 12,
    marginBottom: 12,
  },
  pickerWrapper: {
    borderWidth: 1, borderColor: '#ccc', borderRadius: 8,
    marginBottom: 12, overflow: 'hidden',
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
