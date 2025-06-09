/** 変換表を入れる場所 */
var GSI = {};

const addressEle = document.querySelector('#address');
document.getElementById("test").onclick = function() {
    //位置情報取得をするメソッド
    navigator.geolocation.getCurrentPosition(success,error);
}

//成功したとき
async function success(position){
    // 緯度を取得し画面に表示
    var latitude = position.coords.latitude;
    document.getElementById("latitude").innerHTML = latitude;
    // 経度を取得し画面に表示
    var longitude = position.coords.longitude;
    document.getElementById("longitude").innerHTML = longitude;

    // 逆ジオコーディング API
    var url = new URL('https://mreversegeocoder.gsi.go.jp/reverse-geocoder/LonLatToAddress');
    url.searchParams.set("lat" , latitude); //緯度
    url.searchParams.set("lon" , longitude); //経度
    const response = await fetch(url);
    const json = await response.json();
    const jsondata = json.results; //データを格納

    // 変換表から都道府県などを取得
    const muniData = GSI.MUNI_ARRAY[json.results.muniCd];
    // 都道府県コード,都道府県名,市区町村コード,市区町村名 に分割
    const [prefCode, pref, muniCode, city] = muniData.split(',');

    //取得した位置情報を格納
    const location = `${pref} ${city} ${jsondata.lv01Nm}`;

    //現在地表示
    document.getElementById("address").innerHTML = location;

    
    //PHPにデータをPOST形式で送る処理
    const req = await fetch("http://localhost/JVM_Team/damageDetails.php",{
        method : "POST",
        headers : {
            contentType: "application/json",
        },
        body : JSON.stringify({
            "loc" : location,
        })
    })
}

function error(){
    alert("位置情報が取得できませんでした");
}

