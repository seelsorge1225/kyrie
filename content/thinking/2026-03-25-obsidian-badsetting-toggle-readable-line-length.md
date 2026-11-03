---
title: "Obsidianの「読みやすい行の長さ」が読みづらい問題を解決する"
date: 2026-03-25
folder: thinking
slug: "obsidian-badsetting-toggle-readable-line-length"
link: "https://kyrie-eleison.net/2026/03/25/obsidian-badsetting-toggle-readable-line-length/"
author: "峰(mine)"
categories: 
  - "pc-else"
tags: 
  - app
published: 2026-11-03T06:16:16+00:00
---
Obsidianの「読みやすい行の長さ」は、個人的にはちょっと読みづらい。
**ちょっとだけ**幅が狭いように感じるのだ。

![yomiyasui01.png](https://pub-aa20ad6e84bc4a38a975f3fbc60a3e30.r2.dev/2026/yomiyasui01.png)

これをもっと広く、横幅を自由に設定できる方法を知ったので備忘録もかねてまとめていきたい。
簡単に言うとこんな感じにできる。

![yomiyasui05.png](https://pub-aa20ad6e84bc4a38a975f3fbc60a3e30.r2.dev/2026/yomiyasui05.png)

ちょっと広くなっているのがお分かりだろうか。
このちょっとの差が、一覧性をかなり上げてくれるので作業効率も上がるのだ。

設定は意外と簡単だ。
Obsidianの設定画面から、「外観」の一番下にある「ＣＳＳスぺニット」という項目を探す。
そこにあるフォルダアイコンを開くと、空のディレクトリが開くと思う。

![yomiyasui02.png](https://pub-aa20ad6e84bc4a38a975f3fbc60a3e30.r2.dev/2026/yomiyasui02.png)

そこに、ＣＳＳファイルを作成する（最初はtxtファイルで作成して、名前の変更で「.css」に拡張子を変えればよい）。
タイトルはなんでもいいが「File-line-width」にでもしておこう。
中身はこのように記載する。

```
`body {
  --file-line-width: 900px;
}`
```

cssファイルを保存したら、先ほどの設定画面に戻ってもらって、リロードアイコンを押してもらうと、スぺニットが読み込まれて、「file-line-width」ボタンをオンにできるようになる。

![yomiyasui03.png](https://pub-aa20ad6e84bc4a38a975f3fbc60a3e30.r2.dev/2026/yomiyasui03.png)

これをオンにすると、指定した値の幅でエディタを表示できるようになる。
なお、「900px」のところは横幅だ。自由に数値を調整できる。
900だと広すぎるなら、800とかにしてもいい。
Obsidianを開きながらcssファイルを変更して保存するとその場で幅が変わるので、ちょうどいい幅を模索してほしい。

![yomiyasui06.png](https://pub-aa20ad6e84bc4a38a975f3fbc60a3e30.r2.dev/2026/yomiyasui06.png)

比べてみると、結構見え方が違うと思う。
Obsidianの「読みやすい行の長さ」、全然読みやすくねーぞ！という人は試してみてほしい。

