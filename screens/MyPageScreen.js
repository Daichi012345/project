import React, { useContext } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Alert } from 'react-native';
import { UserContext } from '../contexts/UserContext';

const MyPageScreen = ({ navigation }) => {
  const { user, setUser } = useContext(UserContext);

  const handleLogout = () => {
    Alert.alert('ログアウトしますか？', '', [
      { text: 'キャンセル', style: 'cancel' },
      {
        text: 'ログアウト',
        style: 'destructive',
        onPress: () => {
          setUser(null);
          navigation.reset({
            index: 0,
            routes: [{ name: 'Login' }],
          });
        },
      },
    ]);
  };

  if (!user) {
    return (
      <View style={styles.container}>
        <Text style={styles.info}>ユーザー情報が見つかりません</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>マイページ</Text>

      <View style={styles.infoBox}>
        <Text style={styles.label}>名前：</Text>
        <Text style={styles.info}>{user.name}</Text>

        <Text style={styles.label}>メール：</Text>
        <Text style={styles.info}>{user.email}</Text>

        <Text style={styles.label}>年齢：</Text>
        <Text style={styles.info}>{user.age}</Text>

        <Text style={styles.label}>性別：</Text>
        <Text style={styles.info}>{user.gender}</Text>

        <Text style={styles.label}>アレルギー：</Text>
        <Text style={styles.info}>{user.allergy || 'なし'}</Text>
      </View>

      <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
        <Text style={styles.logoutText}>ログアウト</Text>
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, padding: 24, backgroundColor: '#fff' },
  title: { fontSize: 24, fontWeight: 'bold', textAlign: 'center', marginBottom: 20 },
  infoBox: { marginBottom: 30 },
  label: { fontSize: 16, fontWeight: 'bold', marginTop: 12 },
  info: { fontSize: 16, color: '#333' },
  logoutButton: {
    backgroundColor: '#FF6347',
    padding: 14,
    borderRadius: 10,
    alignItems: 'center',
  },
  logoutText: { color: '#fff', fontWeight: 'bold', fontSize: 16 },
});

export default MyPageScreen;
