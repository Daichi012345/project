// App.js
import React, { useState } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { StatusBar } from 'expo-status-bar';
import { UserContext } from './contexts/UserContext';

import HomeScreen from './screens/HomeScreen';
import MoodInputScreen from './screens/MoodInputScreen';
import MealSuggestionScreen from './screens/MealSuggestionScreen';
import HistoryScreen from './screens/HistoryScreen';
import RegisterScreen from './screens/RegisterScreen';
import LoginScreen from './screens/LoginScreen';
import RecipeDetailScreen from './screens/RecipeDetailScreen';
import MyPageScreen from './screens/MyPageScreen';
import ExerciseInputScreen from './screens/ExerciseInputScreen';

const RootStack = createNativeStackNavigator();
const MainStack = createNativeStackNavigator();
const Tab = createBottomTabNavigator();

function HomeStack() {
  return (
    <MainStack.Navigator>
      <MainStack.Screen name="HomeScreen" component={HomeScreen} options={{ title: 'トップ' }} />
      <MainStack.Screen name="MoodInputScreen" component={MoodInputScreen} />
      <MainStack.Screen name="MealSuggestionScreen" component={MealSuggestionScreen} />
      <MainStack.Screen name="RecipeDetailScreen" component={RecipeDetailScreen} />
    </MainStack.Navigator>
  );
}

function MainTabs() {
  return (
    <Tab.Navigator>
      <Tab.Screen name="HomeTab" component={HomeStack} options={{ title: 'ホーム' }} />
      <Tab.Screen name="HistoryScreen" component={HistoryScreen} options={{ title: '履歴' }} />
      <Tab.Screen name="ExerciseInput" component={ExerciseInputScreen} options={{ title: '運動入力' }} />
      <Tab.Screen name="MyPage" component={MyPageScreen} options={{ title: 'マイページ' }} />
    </Tab.Navigator>
  );
}

export default function App() {
  const [user, setUser] = useState(null);

  return (
    <UserContext.Provider value={{ user, setUser }}>
      <NavigationContainer>
        <StatusBar style="auto" />
        <RootStack.Navigator screenOptions={{ headerShown: false }} initialRouteName="RegisterScreen">
          <RootStack.Screen name="RegisterScreen" component={RegisterScreen} />
          <RootStack.Screen name="Login" component={LoginScreen} />
          <RootStack.Screen name="MainTabs" component={MainTabs} />
        </RootStack.Navigator>
      </NavigationContainer>
    </UserContext.Provider>
  );
}
