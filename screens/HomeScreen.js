import React, { useEffect, useState, useContext } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  ActivityIndicator,
} from 'react-native';
import { useFocusEffect } from '@react-navigation/native';
import { UserContext } from '../contexts/UserContext';
import { API_BASE_URL } from '@env';

export default function HomeScreen({ navigation }) {
  const { user } = useContext(UserContext);
  const [recent, setRecent] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchRecentSuggestions = async () => {
    if (!user || !user._id) {
      setRecent([]);
      setLoading(false);
      return;
    }

    try {
      const res = await fetch(`${API_BASE_URL}/api/recommend/${user._id}`);
      const data = await res.json();
      const sorted = data.sort(
        (a, b) => new Date(b.createdAt) - new Date(a.createdAt)
      );
      setRecent(sorted.slice(0, 3)); // 最新3件
    } catch (err) {
      console.error('提案取得失敗:', err);
      setRecent([]);
    } finally {
      setLoading(false);
    }
  };

  useFocusEffect(
    React.useCallback(() => {
      setLoading(true);
      fetchRecentSuggestions();
    }, [user])
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>気分で選ぶ食事AI</Text>

      {user?.name && <Text style={styles.welcome}>ようこそ、{user.name}さん！</Text>}

      <TouchableOpacity
        style={styles.button}
        onPress={() => navigation.navigate('MoodInputScreen')}
      >
        <Text style={styles.buttonText}>気分・体調を入力する</Text>
      </TouchableOpacity>

      <TouchableOpacity
        style={styles.historyButton}
        onPress={() => navigation.navigate('HistoryScreen')}
      >
        <Text style={styles.historyButtonText}>履歴をみる</Text>
      </TouchableOpacity>

      <Text style={styles.subTitle}>最近の提案</Text>

      {loading ? (
        <ActivityIndicator size="large" color="#4CAF50" />
      ) : recent.length === 0 ? (
        <Text style={styles.noData}>最近の提案がありません</Text>
      ) : (
        <ScrollView horizontal showsHorizontalScrollIndicator={false}>
          {recent.map((item) => (
            <View key={item._id} style={styles.card}>
              <Text style={styles.cardTitle}>{item.meal}</Text>
              <Text style={styles.cardMood}>気分：{item.mood}</Text>
              <Text style={styles.cardDate}>
                {new Date(item.createdAt).toLocaleDateString()}
              </Text>
            </View>
          ))}
        </ScrollView>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff', padding: 24, paddingTop: 64 },
  title: { fontSize: 28, fontWeight: 'bold', marginBottom: 32, textAlign: 'center' },
  welcome: { fontSize: 18, marginBottom: 16, textAlign: 'center' },
  button: {
    backgroundColor: '#4CAF50',
    paddingVertical: 16,
    paddingHorizontal: 24,
    borderRadius: 12,
    marginBottom: 16,
  },
  buttonText: { color: '#fff', fontSize: 18, textAlign: 'center' },
  historyButton: {
    backgroundColor: '#ddd',
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 10,
    marginBottom: 24,
  },
  historyButtonText: { color: '#333', fontSize: 16, textAlign: 'center', fontWeight: 'bold' },
  subTitle: { fontSize: 20, marginBottom: 12 },
  noData: { textAlign: 'center', color: '#999', marginTop: 20 },
  card: {
    backgroundColor: '#F0F0F0',
    padding: 16,
    borderRadius: 12,
    marginRight: 12,
    minWidth: 180,
  },
  cardTitle: { fontSize: 16, fontWeight: '600' },
  cardMood: { fontSize: 14, color: '#555', marginTop: 8 },
  cardDate: { fontSize: 12, color: '#999', marginTop: 4 },
});
