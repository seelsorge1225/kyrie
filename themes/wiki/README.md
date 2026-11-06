# Wiki

Tomos向けの、検索・閲覧・項目間の移動を中心にした百科事典型の追加テーマです。

- Theme version: `1.0.0`
- Required Tomos: `1.1.0`以上

## 特徴

- 白背景、黒文字、青いリンクを基本にした情報中心のレイアウト
- デスクトップでは左ナビゲーション・本文・右補助情報の3カラム構成
- Coreが生成する `page.toc` を右カラムに表示
- Coreのサイト全体タグ情報、パンくず、サイト内検索、Markdown本文を利用
- `page.toc`、主要なタグ、明示的な内部リンクによる関連項目を表示
- JavaScript、投稿者プロフィール、SNS用UIを使用しない

## 配布

このテーマはTomos本体の初期同梱テーマではありません。Tomos本体の初期テーマ一覧、配布ZIP、自動セットアップ設定には追加していません。

### Tomos Postから導入する場合

1. `tomos-wiki-1.0.0.zip`を用意します。
2. Tomos Postのテーマ管理画面からTheme ZIPをアップロードします。
3. 検証に成功したら、テーマを選択して有効化します。

ZIPは最上位ディレクトリ`wiki/`を含むTheme package構造で配布します。Tomos `1.1.0`未満では利用できません。

### 主なContext

- `page.toc`: 記事内見出しから生成された目次HTML
- `tag.items`: サイト全体のタグ名・URL・記事数
- `nav.all_url`: 公開中ページ一覧(`/all/`)へのURL
- `page.related_items`: 本文から明示的にリンクされた公開記事のタイトル・URL

## 構成

- `theme.json`
- `templates/layout.html`
- `templates/page.html`
- `templates/list.html`
- `assets/style.css`
- `assets/favicon.svg`
