# AI Lab（/products）制作リファレンス — Refik Anadol研究の要点

> 2026-07-14作成。完全版は claude.file リポジトリの
> `strategy/refik-anadol-research-2026-07.md`（作家研究・学術文献・技術系譜）と
> `strategy/hiroshima-data-art-2026-07.md`（広島データ源カタログ・検証済み）を参照。
> このファイルはLabページ開発時に手元で見る抜粋。

## 制作思想（/productsヒーローが従う5原則）

1. **Data as pigment** — データは題材ではなく顔料。パレット・波形・粒子は実データから生成する
2. **Machine hallucination** — AIの実用（分類・正確さ）の逆を行き、夢・非合理を可視化する
3. **Collective memory** — データセット＝都市の集合的記憶。会社史が薄くても「広島の記憶」で拡張する
4. **環境入力** — 本家Unsupervisedはロビーの光・動き・音量・NYの天候が生成に影響。Web版は潮位・気象・月齢・ポインタ・スクロール
5. **データの物語を明示** — 固有名詞と数字（例: MoMA 138,151点）。キャプションに「何のデータで描いているか」を必ず書く。これが「きれいなだけのラバランプ」批判（Ben Davis/Jerry Saltz）への最良の防御

## 本家パイプライン ↔ 現行シェーダー対応

| Anadol | /products 現状 | 発展方針 |
|---|---|---|
| データ収集・キュレーション | なし（手打ちパレット） | P1: 地理院DEM＋NDL/Met/AIC歴史画像→パレットJSON化 |
| StyleGAN2-ADA学習（1024次元） | なし | （Phase2以降・任意）latent walk事前計算テクスチャ |
| UMAPデータユニバース（x,y,z,r,g,b,time 7次元） | fbmノイズ空間 | データ駆動パラメータ化で近似 |
| Latent Space Browser（slerp補間の潜在散歩） | uTime＋シードによるfbm時間発展 | 実データ（潮位・月齢）が散歩経路を決める |
| 流体ソルバー＋RTXレイトレ（原典: Stam "Stable Fluids" 1999） | ドメインワープfbm＋解析法線＋加算グロー | 十分。曲率キャビティ陰影の追加で厚み向上可 |
| リアルタイム環境センサ | uMouse | 広島検潮所潮位（15秒）・アメダス風・月齢 |

## 広島データの実装順（詳細は hiroshima-data-art-2026-07.md）

- **P1 静的焼き込み**: 地理院標高タイル（z14/14219/6523等・商用可）→波の基底地形／NDL・Met・AIC（CC0）→時代パレット／かえで紅葉CSV（広島=765・平年値11/22）→季節位相
- **P2 リアルタイム**: 検潮所166744の15秒潮位＋偏差→波高・荒れ／アメダス67437→乱流／SunCalc or Astronomy Engineで月齢→**月齢15〜17帯で月光ピーク＋月の出に立ち上がる（=いざよいの語源）**
- **P3 キャプション**: 「Setouchi tide (live) × 73 years of maple phenology × palettes from 1900s Itsukushima postcards」形式＋出典クレジット欄
- **⚠️ Open-Meteo無料枠は非商用限定** — 本番HPでは使わない（気象庁系で代替）

## 2025-26の新作から得たヒント

- **分野特化モデルの命名**が物語になる: Large Nature Model → Large Architecture Model（Gehry 2025・建築写真3,214万枚）。IZAYOIなら「Setouchi Model」「Hiroshima Memory Model」のような命名で語れる
- **Gemini統合**（Google本社常設 2025-12）: 生成パイプラインへのLLM組込が最新動向
- **Winds of Yawanawá**（2023）: データ提供者への収益還元＝許諾ベースの先行実践。クライアントデータを使う際の participation モデルの参考
- 本人の理論テキストは Architectural Design誌「Space in the Mind of a Machine」(2022, DOI 10.1002/ad.2810) に集約。Labページの英語コピーの語彙源に使える

## 習作（HPとは別・デスクトップ）

- `winds-of-hiroshima_data-sphere.html` — Sphere習作（実在恒星900個＋広島実況気象）
- `unsupervised-study_machine-hallucinations.html` — Unsupervised習作（MoMA実データ16万点の分布＋NYC実況）
