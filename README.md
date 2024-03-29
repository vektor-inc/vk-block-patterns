# vk-block-patterns

Custom Block Patterns by Vektor

```
npm run sass
```

## CSS クラス名

| テーブルクラス |  |
|-| ------------- |
| vk-table--mobile-block | モバイル時縦積み  |
| vk-table--th--width25 | 左セル幅25%  |
| vk-table--th--width30 | 左セル幅30%  |
| vk-table--th--width35 | 左セル幅35%  |
| vk-table--th--width40 | 左セル幅40%  |
| vk-table--th--bg-bright | 左セル背景色  |

| カラムクラス |  |
|-| ------------- |
| vk-cols--reverse | カラムreverse  |

## CSS命名規則

基本形式

vk-[ 識別名 ]--[ 属性値 ]

他のリポジトリでは属性名を含んでいるが、パターンにおいては識別名が属性名のようなものなので
パターンでは属性名は省略して属性値だけとしている

#### 他のリポジトリの命名例

Lightning G3 : [ コンポーネント名 ]--[ 属性名 ]--[ 属性値 ]
VK Blocks : [ prefix ]_[ ブロック名 ]-[ 属性名 ]-[ 属性値 ]


例)
vk-cols--fit
vk-cols--hasbtn
vk-aligncenter--mobile

* キャメルケース・アンダーバーは使用しない（ExUnitなど他のDOM要素の上書きは仕方ない）
* 英単語の連結及びコンポーネント名と要素名の連結はハイフン（-）
* FLOCSSのように要素の前に l- p- c- をつけるのは開発者にある程度のノウハウが必要とされるので不採用とした
* ハイフンだけでは 単語の区切りか DOM の階層かわからんやんけ！と思われるかもしれないが、実際問題階層は省略できる部分は省略した方がCSSが短くできる＆必要なのは概ねコンポーネント名と要素名だけなので問題にならない。

