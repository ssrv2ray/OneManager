# NOTICE: the release is used as archive. 
# 注意：release只是用来存档的。
Please read the descriptions of settings before raising an issue. 请将设置中所有的设置项的说明都读一遍，有些问题就不用问了。  

# Deploy to Heroku  
Official: https://heroku.com  
Demo: https://index.herokuapp.com/  

How to Install:   
> ~~Click the button [![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/qkqpttgf/OneManager-php) to Deploy a new app~~(`"We couldn't deploy your app because the source code violates the Salesforce Acceptable Use and External-Facing Services Policy."`)  
> Fork this project, create an heroku app, then turn to Deploy tab, deploy via connect to your github fork.   


# Deploy to Glitch  
Official: https://glitch.com/  
Demo: https://onemanager.glitch.me/  

How to Install: New Project -> Import form Github -> paste "https://github.com/qkqpttgf/OneManager-php", after done, Show -> In a New Window.  


# Deploy to Virtual Private Server (VPS 或空间)  
DEMO:  无  
How to Install:  
    1.Start web service on your server (httpd or other), make sure you can visit it.  
    启动web服务器，确保你能访问到。  
    2.Make the rewrite works, the rule is in .htaccess file, make sure any query redirect to index.php.  
    开启伪静态(重写)功能，规则在.htaccess文件中，ngnix从里面复制，我们的目的是不管访问什么都让index.php来处理。  
    3.Upload code.  
    上传好代码。  
    4.Change the file .data/config.php can be read&write (666 is suggested).  
    使web身份可读写代码中的.data/config.php文件，推荐chmod 666 .data/config.php。  
    5.View the website in chrome or other.  
    在浏览器中访问。  


# Features 特性  
When downloading files, the program produce a direct url, visitor download files from MS OFFICE via the direct url, the server expend a few bandwidth in produce.  
下载时，由程序解析出直链，浏览器直接从微软Onedrive服务器下载文件，服务器只消耗与微软通信的少量流量。  
When uploading files, the program produce a direct url, visitor upload files to MS OFFICE via the direct url, the server expend a few bandwidth in produce.  
上传时，由程序生成上传url，浏览器直接向微软Onedrive的这个url上传文件，服务器只消耗与微软通信的少量流量。  
The XXX_path in setting is the path in Onedrive, not in url, program will find the path in Onedrive.  
设置中的 XXX_path 是Onedrive里面的路径，并不是你url里面的，程序会去你Onedrive里面找这个路径。  
LOGO ICON: put your 'favicon.ico' in the path you showed, make sure xxxxx.com/favicon.ico can be visited.   
网站图标：将favicon.ico文件放在你要展示的目录中，确保 xxxxx.com/favicon.ico 可以访问到。  
Program will show content of 'readme.md' & 'head.md'.  
可以在文件列表显示head.md跟readme.md文件的内容。  
guest up path, is a folder that the guest can upload files, but can not be list (exclude admin).  
游客上传目录（也叫图床目录），是指定一个目录，让游客可以上传文件，不限格式，不限大小。这个目录里面的内容不列清单（除非管理登录）。  
If there is 'index.html' file, program will only show the content of 'index.html', not list the files.  
如果目录中有index.html文件，只会输出显示html文件，不显示程序框架。  
Click 'EditTime' or 'Size', the list will sort by time or size, Click 'File' can resume sort.  
点击“时间”、“大小”，可以排序显示，点“文件”恢复原样。  

# Functional files 功能性文件  
### favicon.ico  
put it in the showing home folder of FIRST disk (maybe not root of onedrive). 放在第一个盘的显示目录（不一定是onedrive根目录）。  
### index.html  
show content of index.html as html. 将index.html以静态网页显示出来。  
### head.md readme.md  
it will showed at top or bottom as markdown. 以MD语法显示在顶部或底部。  
### head.omf foot.omf  
it will showed at top or bottom as html (javascript works!). 以html显示在顶部或底部（可以跑js）。  