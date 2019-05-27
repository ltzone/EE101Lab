# EE101Lab
2019 Spring Semester EE101 Final Project Working Space :D

# Notice 0527

index.php中已由方少恒改成整合搜索框的形式，为此需要在solr admin中进行如下配置

1.Add Field:

name: keyword

field type: text_en

stored:Y

indexed:Y

multivalued:Y

required:N

2.Add Copy Field:(3 times)

source: PaperName  destination: keyword

source: AuthorName  destination: keyword

source: ConferenceName  destination: keyword

配置完成后需重新跑一遍Solrwrite.py

# WIKI V1.0 0527

已实现功能：

1. Integrated Search Bar by fsh
2. conference.php/paper.php by yhb (half completed)
3. Echarts demo by zlt (expecting to be promoted)
4. Leaf flipping (of SQL results) by dsw (still working)

待修复BUG：

- conference.php/paper.php 中超链接的完善
- search result 中对无结果的提示
- paper.php 中reference的显示
- conference.php 中显示结果太多，需要翻页
- search (solr) result 的翻页


## Notice 0521
请在星期五之前将github相关的配置完成

同时请参考config_guide中的说明在本地创建名称为FINAL的database和solr core，保持我们以后数据和配置的一致

为避免origin与本地代码的冲突，每个人都在名称为自己缩写的分支上独立开发，在需要的时候merge到master分支上，不允许直接在master分支上的commit


### 有关配置的补充说明：
git本地，第三步复制SSH链接先跳过，等SSH KEY创建完成后再复制，clone

最好不要包含中文路径


## 第一周分工 截止日期 5.27（星期日）

周李韬：网站页面优化，增加更多可视化功能，使用bootstrap等css库对页面样式进行美化。

杨弘博：在网站中增加paper与conference页面

董世文：每个页面在展示信息时，如果展示的信息条目较多（如搜索结果、论文列表等），则每页显示10条，并添加翻页功能。

方少恒：搜索不再是3个独立的搜索框，而是一个多功能搜索框，用户可以在该搜索框内输入多种类型的字段，网站系统会通过solr在多个字段中进行混合搜索（参考solr的copy field功能）
