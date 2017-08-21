# pieni
CodeIgniterライクなPHPフレームワーク

## pieniについて
pieniはLAMP環境で動作するPHPフレームワークです。  
CodeIgniterの影響を大きく受けています。  
CRUDと認証機能が含まれます。  
強力なモデルによりコントローラやビューを作成することなくプロトタイピングを行うことができます。  
コントローラ、モデル、ライブラリ、ビュー、言語ファイルなど、ほとんどのファイルはフォールバックによって、然るべきパスからロードされます。  
これはあらゆるファイルをあなたのパッケージに含めることができ、また置換が可能ということを意味します。  

## インストール
pieniはvendor/sagittar-org/pieniへ展開されます。  
下記の様にindex.phpと.htaccessをコピーし、データベースpieniを作成してください。  
サンプルアプリケーションが動作します。  
```
cp ./vendor/sagittar-org/pieni/index.php .
cp ./vendor/sagittar-org/pieni/.htaccess .
mysql < ./vendor/sagittar-org/pieni/misc/pieni.dump
```

## リクエスト
.htaccessでの指定からdirectから始まらない全てのURIはindex.phpによって処理されます。  

## パッケージ
ロードの検索バスに含ませるパッケージはindex.phpのpackage_listで指示します。  

## URIセグメント
セグメント情報はuri()で参照します。  
第1から第4セグメントは、言語、アクター、クラス、メソッドに対応します。  
言語、アクター、クラスが取り得る値はconfig.phpから配列で指示されます。  
0番目の要素がデフォルト値で、これらは省略されなければいけません。  
クラスがconfig.phpで指示されるtable_listに含まれる場合、第5から第7セグメントは、ID、エイリアス、親IDに対応します。  
上記以降のセグメントはクラスメソッドの引数に渡されます。  
URIdocs/index/a/b/cでの例を示します。  
```
Array
(
    [uri_string] => docs/index/a/b/c
    [param_arr] => Array
        (
            [0] => a
            [1] => b
            [2] => c
        )

    [language] => ja
    [actor] => g
    [class] => docs
    [method] => index
)
```

## コントローラのロード
検索バスからuri('class')に対応するコントローラをロードします。  
uri('class')がtable_listに含まれる場合、コントローラCrudも検索パスに含まれます。  
これは独自のビジネスロジックがない場合、コントローラが不要であることを意味します。  

## モデルのロード
コントローラがCrudを継承する場合、uri('class')に対応するモデルがロードされます。  

## クラスメソッドの実行
コントローラのuri('method')に対応するメソッドを実行します。  

## ビューのロード
Ajaxではないリクエストでは、多くの場合クラスメソッドからビューをロードします。  
ビューからも他のビューをネストしてロードできます。  
ビューをカスタマイズする場合は、あなたのパッケージバスにビューファイルを設置します。  
pieniが提供するビューは最終フォールバック先であり編集も削除も必要ありません。  
