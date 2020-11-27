# Movie Info 

:movie_camera: Get movie information from Internet.

## Warning

:warning:  Get the basic information from the current project. This project is only used for learning and research. The corresponding API address of Douban and the corresponding apikey need to be configured for project start-up,At present, Douban has cancelled the application for key, but some reserved keys have been circulated. Please search by yourself.In addition, no matter the applet or app interface path and key are also applicable to this project, but please study the details by yourself. This project is not responsible for obtaining API path and apikey, and also refuses to provide and help. This is against the basic interests of Douban, please try to stay away from it!


:warning: 目前本项目部分基础信息从豆瓣获取。本项目仅供学习研究使用，项目启动需要配置对应的豆瓣API地址和对应的apikey,
目前豆瓣取消了key的申请，但是有部分保留的key被流传，请自行搜索。
另外，无论小程序还是app的接口路径和key也都适用本项目，但是详细信息请自行研究，本项目不负责具体获取api路径和apikey，也拒绝提供信息和帮助，这是有悖于豆瓣基本利益的事情，请尽量远离！

## Installing

    ```sh
    $ composer require wangqs/movie-info
    ```


## Usage

### Demo

you can do it as simple as:

```php
    $config = [
        'channel' => 1,      //Search by Douban ID (1) or IMDB ID (2)
        'baseUrl' => 'Fill in the basic path of Douban API here', //这里填入豆瓣API的基础路径
        'apiKey'  => 'Fill in apikey of Douban', //这里填入豆瓣的apikey
        'returnsFormat'  => 'array or json', //这里设置返回数据的格式（默认是数组），填入json 如果返回为数组的化，则自动转化为json
	];

    $movie = new Wangqs\Movie\Movie('imdbId or dbId',$config);

    //details info 详细信息
    $info = $movie->all();

    //original info 原始信息
    $info = $movie->original();

    //movie title (you can setting true get details title by array)  传参true 可以获取到更多的标题信息
    $title = $movie->title(true);  //Basis(false) or details(true)

    //movie rating array
    $rating = $movie->rating();

    //movie pub date of year
    $year = $movie->year();

    //photo  (you can setting true get details title by array)  传参true 可以获取到更多的信息
    $photo = $movie->photo(false); //Basis(false) or details(true)

    //intro 详细介绍
    $intro = $movie->intro();

    //genres    分类
    $genres = $movie->genres();

    //actors    演员
    $actors = $movie->actors();

    //directors     导演
    $directors = $movie->directors();

    //tags      标签
    $tags = $movie->tags();

    //durations 电影时长
    $runtime = $movie->runtime();

    //Publishing region (country) 发布地区（国家）
    $countries = $movie->countries();
``` 

### Response Instructions
```json
{
    "db_id":"3592854",
    "imdb_id":"tt1392190",
    "title":"疯狂的麦克斯4：狂暴之路",
    "title_card_subtitle":"2015 / 澳大利亚 南非 / 动作 科幻 冒险 / 乔治·米勒 / 汤姆·哈迪 查理兹·塞隆",
    "original_title":"Mad Max: Fury Road",
    "aka":[
        "末日先锋：战甲飞车(港)",
        "疯狂麦斯：愤怒道(台)",
        "冲锋飞车队4",
        "迷雾追魂手4",
        "冲锋追魂手4",
        "疯狂麦克斯4",
        "疯狂迈斯：怒途",
        "Mad Max 4: Fury Road"
    ],
    "db_rating":"8.6",
    "imdb_rating":"8.1",
    "pub_date":[
        "2015-05-14(澳大利亚)"
    ],
    "year":"2015",
    "pic":{
        "large":"https://img2.doubanio.com/view/photo/m_ratio_poster/public/p2236181653.jpg",
        "normal":"https://img2.doubanio.com/view/photo/s_ratio_poster/public/p2236181653.jpg"
    },
    "intro":"未来世界，水资源短缺引发了连绵的战争。人们相互厮杀，争夺有限的资源，地球变成了血腥十足的杀戮死战场。面容恐怖的不死乔在戈壁山谷建立了难以撼动的强大武装王国，他手下的战郎驾驶装备尖端武器的战车四下抢掠，杀伐无度，甚至将自己的孩子打造成战争机器。在最近一次行动中，不死乔的得力战将弗瑞奥萨（查理兹·塞隆 Charlize Theron 饰）带着生育者们叛逃，这令不死乔恼羞成怒，发誓要追回生育者。经历了激烈的追逐战和摧毁力极强的沙尘暴，弗瑞奥萨和作为血主的麦克斯（汤姆·哈迪 Tom Hardy 饰）被迫上路，而身后不仅有不死乔的追兵，还有汽油镇、子弹农场的重兵追逐。
末世战争，全面爆发……",
    "languages":[
        "英语",
        "俄语"
    ],
    "genres":"未来世界，水资源短缺引发了连绵的战争。人们相互厮杀，争夺有限的资源，地球变成了血腥十足的杀戮死战场。面容恐怖的不死乔在戈壁山谷建立了难以撼动的强大武装王国，他手下的战郎驾驶装备尖端武器的战车四下抢掠，杀伐无度，甚至将自己的孩子打造成战争机器。在最近一次行动中，不死乔的得力战将弗瑞奥萨（查理兹·塞隆 Charlize Theron 饰）带着生育者们叛逃，这令不死乔恼羞成怒，发誓要追回生育者。经历了激烈的追逐战和摧毁力极强的沙尘暴，弗瑞奥萨和作为血主的麦克斯（汤姆·哈迪 Tom Hardy 饰）被迫上路，而身后不仅有不死乔的追兵，还有汽油镇、子弹农场的重兵追逐。
末世战争，全面爆发……",
    "actors":[
        {
            "db_actor_id":"1049489",
            "name":"汤姆·哈迪",
            "roles":[
                "演员",
                "制片人",
                "编剧"
            ],
            "abstract":"　　汤姆·哈迪给人留下的最初印象是《兄弟连》和《黑鹰坠落》中英俊而带有几分邪气的美国大兵，但他是...",
            "cover_url":"https://img3.doubanio.com/view/celebrity/s_ratio_celebrity/public/p5110.jpg"
        },
        {
            "db_actor_id":"1018991",
            "name":"查理兹·塞隆",
            "roles":[
                "演员",
                "制片人",
                "配音",
                "导演"
            ],
            "abstract":"查理兹·塞隆生于南非，外型极为靓丽的她是混血独生女。父亲是法国人，母亲则是德国人。她6岁时即开始学...",
            "cover_url":"https://img3.doubanio.com/view/celebrity/s_ratio_celebrity/public/p44470.jpg"
        },
        {
            "db_actor_id":"1036341",
            "name":"尼古拉斯·霍尔特",
            "roles":[
                "演员",
                "配音"
            ],
            "abstract":"　　 尼古拉斯·霍尔特 (Nicholas Hoult) 1989年12月7日出生于英国东南部伯克夏郡3万多人口的小镇Worki...",
            "cover_url":"https://img9.doubanio.com/view/celebrity/s_ratio_celebrity/public/p1371701601.6.jpg"
        }
    ],
    "directors":[
        {
            "db_actor_id":"1056046",
            "name":"乔治·米勒",
            "roles":[
                "制片人",
                "导演",
                "编剧",
                "副导演",
                "演员"
            ],
            "abstract":"He is a producer and writer, known for Happy Feet (2006), Mad Max 2 (1981) and Mad Max (1979).",
            "cover_url":"https://img1.doubanio.com/view/celebrity/s_ratio_celebrity/public/p33507.jpg"
        }
    ],
    "tags":[
        "暴力片",
        "动作片",
        "公路片",
        "科幻",
        "美国",
        "冒险片",
        "澳大利亚",
        "剧情片"
    ],
    "runtime":[
        "120分钟"
    ],
    "countries":[
        "澳大利亚",
        "南非"
    ]
}
```

## License

MIT
