import React, { useContext, useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Alert, TextInput } from 'react-native';
import { UserContext } from '../contexts/UserContext';
import { API_BASE_URL } from '@env';

const MyPageScreen = ({ navigation }) => {
  const { user, setUser } = useContext(UserContext);
  const [isEditing, setIsEditing] = useState(false);
  const [editedUser, setEditedUser] = useState(user);

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

  const handleSave = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/user/${user._id}`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(editedUser),
      });

      if (!response.ok) {
        throw new Error('サーバーへの保存に失敗しました');
      }

      const data = await response.json();
      setUser(data.user); // 最新情報を反映
      setIsEditing(false);
      console.log('保存されたアレルギー:', editedUser.allergy);

      Alert.alert('保存しました');
    } catch (error) {
      console.error('❌ 保存エラー:', error);
      Alert.alert('エラー', 'ユーザー情報の保存に失敗しました');
    }
  };


  if (!user) {
    return (
      <View style={styles.container}>
        <Text style={styles.info}>ユーザー情報が見つかりません</Text>
      </View>
    );
  }

  const renderField = (label, key) => (
    <>
      <Text style={styles.label}>{label}</Text>
      {isEditing ? (
        <TextInput
          style={styles.input}
          value={editedUser[key]}
          onChangeText={(text) => setEditedUser({ ...editedUser, [key]: text })}
        />
      ) : (
        <Text style={styles.info}>{user[key] || 'なし'}</Text>
      )}
    </>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>マイページ</Text>
      <View style={styles.infoBox}>
        {renderField('名前：', 'name')}
        {renderField('メール：', 'email')}
        {renderField('年齢：', 'age')}
        {renderField('性別：', 'gender')}
        {renderField('アレルギー：', 'allergy')}
      </View>

      {isEditing ? (
        <TouchableOpacity style={styles.saveButton} onPress={handleSave}>
          <Text style={styles.logoutText}>保存する</Text>
        </TouchableOpacity>
      ) : (
        <TouchableOpacity style={styles.editButton} onPress={() => setIsEditing(true)}>
          <Text style={styles.logoutText}>編集する</Text>
        </TouchableOpacity>
      )}

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
  input: {
    fontSize: 16,
    borderBottomWidth: 1,
    borderColor: '#ccc',
    paddingVertical: 4,
    marginBottom: 8,
  },
  editButton: {
    backgroundColor: '#4682B4',
    padding: 14,
    borderRadius: 10,
    alignItems: 'center',
    marginBottom: 10,
  },
  saveButton: {
    backgroundColor: '#32CD32',
    padding: 14,
    borderRadius: 10,
    alignItems: 'center',
    marginBottom: 10,
  },
  logoutButton: {
    backgroundColor: '#FF6347',
    padding: 14,
    borderRadius: 10,
    alignItems: 'center',
  },
  logoutText: { color: '#fff', fontWeight: 'bold', fontSize: 16 },
});

export default MyPageScreen;
