# Movie Info 

:palm_tree: Get movie information from Douban.

## Warning

:construction:  Get the basic information from the current project. This project is only used for learning and research. The corresponding API address of Douban and the corresponding apikey need to be configured for project start-up,At present, Douban has cancelled the application for key, but some reserved keys have been circulated. Please search by yourself.In addition, no matter the applet or app interface path and key are also applicable to this project, but please study the details by yourself. This project is not responsible for obtaining API path and apikey, and also refuses to provide and help. This is against the basic interests of Douban, please try to stay away from it!


:construction: 目前本项目部分基础信息从豆瓣获取。本项目仅供学习研究使用，项目启动需要配置对应的豆瓣API地址和对应的apikey,
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


## License

MIT
