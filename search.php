<?php
/**
 * 搜索页模板 - Steam公告风格
 * 
 * @package SteamAnnouncement
 */

// 导入侧边导航菜单解析函数
function parseSidebarMenu($menuConfig) {
    $menu = array();
    $lines = explode("\n", $menuConfig);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        $parts = explode('|', $line, 2);
        if (count($parts) == 2) {
            $menu[] = array(
                'name' => trim($parts[0]),
                'url' => trim($parts[1])
            );
        }
    }
    
    return $menu;
}

$this->need('header.php');
?>

<div class="page-wrapper">
    <!-- 左侧导航 - Steam风格 -->
    <nav class="main-navigation">
        <ul class="nav-menu">
            <?php
            // 获取侧边导航菜单配置
            $menuConfig = $this->options->sidebarMenu;
            $menuItems = parseSidebarMenu($menuConfig);
            
            foreach ($menuItems as $item) {
                $isActive = false;
                $isGroupTitle = ($item['url'] == '#');
                
                // 检查是否为当前页面
                if (!$isGroupTitle) {
                    $currentUrl = $this->request->getPathinfo();
                    $itemUrl = parse_url($item['url'], PHP_URL_PATH);
                    $isActive = ($currentUrl == $itemUrl);
                }
                
                if ($isGroupTitle) {
                    // 分组标题
                    echo '<li class="nav-item"><span class="nav-group-title">' . $item['name'] . '</span></li>';
                } else {
                    // 普通菜单项
                    echo '<li class="nav-item"><a href="' . $item['url'] . '" class="nav-link' . ($isActive ? ' active' : '') . '">' . $item['name'] . '</a></li>';
                }
            }
            
            // 显示Typecho页面
            $this->widget('Widget_Contents_Page_List')->to($pages);
            if ($pages->have()) {
                echo '<li class="nav-item"><span class="nav-group-title">页面</span></li>';
                while($pages->next()):
                    $isActive = ($this->is('page') && $this->cid == $pages->cid);
                    echo '<li class="nav-item"><a href="' . $pages->permalink() . '" class="nav-link' . ($isActive ? ' active' : '') . '">' . $pages->title() . '</a></li>';
                endwhile;
            }
            ?>
        </ul>
    </nav>
    
    <!-- 主内容区 -->
    <div class="content-container">
        <main class="main-content">
            <div class="container">
                <!-- 搜索结果头部 -->
                <div class="search-header">
                    <h1 class="search-title">
                        搜索结果
                    </h1>
                    <div class="search-info">
                        <p class="search-query">
                            关键词：<strong><?php echo htmlspecialchars($this->request->s); ?></strong>
                        </p>
                        <p class="search-results-count">
                            共找到 <strong><?php $this->archiveCount(); ?></strong> 条相关文章
                        </p>
                    </div>
                </div>
                
                <!-- 搜索结果列表 -->
                <div class="search-results-list">
                    <?php if ($this->have()): ?>
                        <?php while($this->next()): ?>
                        <!-- Steam风格搜索结果卡片 -->
                        <div class="search-result-card">
                            <!-- 左侧封面图 -->
                            <div class="result-thumbnail">
                                <?php
                                    // 获取文章封面图，默认使用Steam风格占位图
                                    $thumbnail = $this->fields->thumbnail;
                                    $thumbnailUrl = $thumbnail ? $thumbnail : 'https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/items/570/d004f45b7d98492e61c1c9d95230589693b4834a.jpg';
                                ?>
                                <a href="<?php $this->permalink() ?>" class="thumbnail-link">
                                    <img src="<?php echo $thumbnailUrl; ?>" alt="<?php $this->title() ?>" class="thumbnail-image">
                                </a>
                            </div>
                            
                            <!-- 中间内容区 -->
                            <div class="result-content">
                                <h2 class="result-title">
                                    <a href="<?php $this->permalink() ?>" class="title-link">
                                        <?php $this->title() ?>
                                    </a>
                                </h2>
                                <div class="result-meta">
                                    <span class="meta-date"><?php $this->date('Y-m-d H:i'); ?></span>
                                    <span class="meta-author">作者: <?php $this->author(); ?></span>
                                    <span class="meta-category">
                                        <?php $this->category(', '); ?>
                                    </span>
                                </div>
                                <!-- 搜索结果摘要 -->
                                <div class="result-excerpt">
                                    <?php
                                        // 优先使用自定义摘要，否则使用自动截取
                                        $customExcerpt = $this->fields->customExcerpt;
                                        if ($customExcerpt) {
                                            echo $customExcerpt;
                                        } else {
                                            $this->excerpt(180, '...');
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <!-- 右侧按钮区 -->
                            <div class="result-actions">
                                <a href="<?php $this->permalink() ?>" class="read-more-btn">
                                    阅读更多
                                </a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- 无搜索结果状态 -->
                        <div class="no-search-results">
                            <div class="no-results-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>未找到相关结果</h3>
                            <p>没有找到与 "<?php echo htmlspecialchars($this->request->s); ?>" 相关的文章</p>
                            <div class="search-suggestions">
                                <h4>搜索建议</h4>
                                <ul>
                                    <li>检查关键词拼写是否正确</li>
                                    <li>尝试使用更通用的关键词</li>
                                    <li>减少关键词数量</li>
                                    <li>尝试使用其他搜索词</li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- 分页 -->
                <div class="pagination">
                    <?php $this->pageNav('上一页', '下一页', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'page-nav', 'itemTag' => 'span', 'textTag' => 'a', 'currentClass' => 'current')); ?>
                </div>
            </div>
        </main>
        
        <?php $this->need('footer.php'); ?>
    </div>
</div>

<!-- 搜索页专用样式 -->
<style>
/* Steam公告风格搜索页样式 */

/* 搜索结果头部 */
.search-header {
    padding: 20px 0;
    margin-bottom: 32px;
    border-bottom: 1px solid rgba(102, 192, 244, 0.2);
}

.search-title {
    font-size: 28px;
    font-weight: bold;
    color: #ffffff;
    margin: 0 0 16px 0;
    line-height: 1.3;
}

.search-info {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
}

.search-query {
    font-size: 14px;
    color: #8f98a0;
    margin: 0;
}

.search-query strong {
    color: #66c0f4;
    font-weight: bold;
}

.search-results-count {
    font-size: 14px;
    color: #8f98a0;
    margin: 0;
}

.search-results-count strong {
    color: #66c0f4;
    font-weight: bold;
}

/* 搜索结果列表 */
.search-results-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Steam风格搜索结果卡片 */
.search-result-card {
    display: flex;
    align-items: center;
    background-color: rgba(26, 38, 51, 0.9);
    border: 1px solid rgba(102, 192, 244, 0.3);
    border-radius: 6px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.search-result-card:hover {
    background-color: rgba(26, 38, 51, 0.95);
    border-color: #66c0f4;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(102, 192, 244, 0.2);
}

/* 左侧封面图 */
.result-thumbnail {
    width: 180px;
    height: 120px;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
}

.thumbnail-link {
    display: block;
    width: 100%;
    height: 100%;
}

.thumbnail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.search-result-card:hover .thumbnail-image {
    transform: scale(1.05);
}

/* 中间内容区 */
.result-content {
    flex: 1;
    padding: 0 24px;
    min-width: 0;
}

.result-title {
    margin: 0 0 8px 0;
    font-size: 18px;
    font-weight: 600;
    line-height: 1.4;
}

.title-link {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.2s ease;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.title-link:hover {
    color: #66c0f4;
    text-decoration: none;
}

.result-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: #8f98a0;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.meta-date, .meta-author, .meta-category {
    display: inline-block;
}

/* 搜索结果摘要 */
.result-excerpt {
    color: #c6d4df;
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* 右侧按钮区 */
.result-actions {
    padding: 0 24px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* 阅读更多按钮 */
.read-more-btn {
    display: inline-block;
    padding: 8px 20px;
    background: linear-gradient(90deg, #66c0f4 0%, #4a90e2 100%);
    color: #ffffff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    border: none;
    border-radius: 3px;
    transition: all 0.2s ease;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.read-more-btn:hover {
    background: linear-gradient(90deg, #4a90e2 0%, #66c0f4 100%);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(102, 192, 244, 0.3);
    color: #ffffff;
    text-decoration: none;
}

.read-more-btn:active {
    transform: translateY(0);
    box-shadow: 0 1px 4px rgba(102, 192, 244, 0.3);
}

/* 无搜索结果状态 */
.no-search-results {
    text-align: center;
    padding: 80px 20px;
    background-color: rgba(26, 38, 51, 0.9);
    border: 1px solid rgba(102, 192, 244, 0.2);
    border-radius: 6px;
    margin-top: 20px;
}

.no-results-icon {
    font-size: 64px;
    color: #8f98a0;
    margin-bottom: 16px;
}

.no-search-results h3 {
    color: #ffffff;
    font-size: 20px;
    margin-bottom: 8px;
}

.no-search-results p {
    color: #8f98a0;
    font-size: 14px;
    margin: 0 0 24px 0;
}

/* 搜索建议 */
.search-suggestions {
    max-width: 500px;
    margin: 0 auto;
    text-align: left;
    background-color: rgba(18, 26, 34, 0.8);
    padding: 20px;
    border-radius: 6px;
    border: 1px solid rgba(102, 192, 244, 0.2);
}

.search-suggestions h4 {
    color: #ffffff;
    font-size: 16px;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.search-suggestions ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.search-suggestions li {
    color: #c6d4df;
    font-size: 14px;
    padding: 6px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-suggestions li:before {
    content: "•";
    color: #66c0f4;
    font-weight: bold;
    font-size: 18px;
    line-height: 1;
}

/* 分页样式 */
.pagination {
    margin-top: 32px;
    padding-top: 20px;
    border-top: 1px solid rgba(102, 192, 244, 0.2);
}

.page-nav {
    display: flex;
    justify-content: center;
    gap: 8px;
}

.page-nav span a, .page-nav .current {
    padding: 6px 12px;
    background-color: rgba(26, 38, 51, 0.8);
    border: 1px solid rgba(102, 192, 244, 0.3);
    color: #ffffff;
    text-decoration: none;
    font-size: 13px;
    border-radius: 3px;
    transition: all 0.2s ease;
}

.page-nav span a:hover {
    background-color: rgba(102, 192, 244, 0.2);
    border-color: #66c0f4;
    text-decoration: none;
}

.page-nav .current {
    background-color: rgba(102, 192, 244, 0.3);
    border-color: #66c0f4;
    font-weight: 600;
}

/* 响应式设计 */
@media (max-width: 1024px) {
    .search-result-card {
        gap: 16px;
        padding: 12px;
    }
    
    .result-thumbnail {
        width: 150px;
        height: 100px;
    }
    
    .result-content {
        padding: 0 16px;
    }
    
    .result-actions {
        padding: 0 16px;
    }
    
    .read-more-btn {
        padding: 6px 16px;
        font-size: 12px;
    }
}

@media (max-width: 768px) {
    .search-result-card {
        flex-direction: column;
        align-items: stretch;
        height: auto;
        padding: 0;
    }
    
    .result-thumbnail {
        width: 100%;
        height: 180px;
    }
    
    .result-content {
        padding: 16px;
    }
    
    .result-actions {
        padding: 0 16px 16px;
        justify-content: flex-start;
    }
    
    .read-more-btn {
        width: 100%;
        text-align: center;
    }
    
    .result-title {
        font-size: 16px;
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
    }
    
    .result-excerpt {
        -webkit-line-clamp: 3;
    }
    
    .search-title {
        font-size: 24px;
    }
    
    .search-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .result-thumbnail {
        height: 140px;
    }
    
    .search-header {
        padding: 16px 0;
        margin-bottom: 24px;
    }
    
    .search-title {
        font-size: 20px;
    }
    
    .search-results-list {
        gap: 12px;
    }
    
    .no-search-results {
        padding: 60px 16px;
    }
    
    .search-suggestions {
        padding: 16px;
    }
}
</style>