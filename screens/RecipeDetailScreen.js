import React, { useState } from 'react';
import { View, Text, StyleSheet, ScrollView, TextInput } from 'react-native';

const RecipeDetailScreen = ({ route }) => {
    const {
        name,
        summary,
        instructions,
        ingredients = [],
        servings: initialServings = 2
    } = route.params;

    const [servings, setServings] = useState(initialServings);
    const [inputServings, setInputServings] = useState(String(initialServings));
    const baseServings = initialServings;

    // 分量を人数に応じて調整する関数
    const adjustAmount = (text) => {
        // 正規表現で最初に一致した "数値＋単位" を探す
        const match = text.match(/(\d+(?:\.\d+)?)(\s*)(カップ|g|ml|本|個|枚|大さじ|小さじ|杯)/);
        if (!match) return text;

        const value = parseFloat(match[1]);
        const space = match[2];
        const unit = match[3];
        const scaled = (value * servings / baseServings).toFixed(1).replace(/\.0$/, '');

        // マッチした部分を置き換える
        return text.replace(match[0], `${scaled}${space}${unit}`);
    };



    return (
        <ScrollView style={styles.container}>
            <Text style={styles.title}>{name}</Text>

            <Text style={styles.sectionTitle}>概要</Text>
            <Text style={styles.text}>{summary || '説明がありません。'}</Text>

            <Text style={styles.sectionTitle}>材料（{servings}人分）</Text>

            <View style={styles.servingsRow}>
                <Text>人数：</Text>
                <TextInput
                    style={styles.input}
                    keyboardType="numeric"
                    value={inputServings}
                    onChangeText={(text) => {
                        setInputServings(text);
                        const num = parseInt(text);
                        if (!isNaN(num) && num > 0) {
                            setServings(num);
                        }
                    }}
                />
            </View>

            <View style={styles.table}>
                {ingredients.length > 0 ? (
                    ingredients.map((item, index) => (
                        <View style={styles.tableRow} key={index}>
                            <Text style={styles.ingredientName}>{adjustAmount(item)}</Text>
                        </View>
                    ))
                ) : (
                    <Text style={styles.text}>材料情報がありません。</Text>
                )}
            </View>


            <Text style={styles.sectionTitle}>作り方</Text>
            <Text style={styles.text}>
                {instructions === 'There is no procedural information.' || !instructions
                    ? '手順情報がありません。'
                    : instructions}
            </Text>
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, padding: 20, backgroundColor: '#fff' },
    title: { fontSize: 22, fontWeight: 'bold', marginBottom: 20 },
    sectionTitle: { fontSize: 18, fontWeight: 'bold', marginTop: 16 },
    text: { fontSize: 16, lineHeight: 24, marginTop: 8 },
    servingsRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 8,
    },
    input: {
        borderWidth: 1,
        borderColor: '#ccc',
        borderRadius: 6,
        padding: 4,
        width: 60,
        marginLeft: 8,
        textAlign: 'center',
    },
    table: {
        borderTopWidth: 1,
        borderColor: '#ccc',
        marginTop: 8,
    },
    tableRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        borderBottomWidth: 1,
        borderColor: '#eee',
        paddingVertical: 8,
    },
    ingredientName: {
        fontSize: 16,
        flex: 1,
    },
    ingredientAmount: {
        fontSize: 16,
        color: '#555',
        textAlign: 'right',
        flex: 1,
    },
});

export default RecipeDetailScreen;
