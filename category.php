<?php
/**
 * 分类页模板 - Steam公告风格
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
                <!-- 分类页头部 -->
                <div class="category-header">
                    <h1 class="category-title">
                        <?php $this->archiveTitle(array('category' => '%s'), '', ''); ?>
                    </h1>
                    <div class="category-info">
                        <span class="category-post-count">
                            共 <strong><?php $this->archiveCount(); ?></strong> 篇文章
                        </span>
                    </div>
                </div>
                
                <!-- 分类文章列表 -->
                <div class="category-posts-list">
                    <?php if ($this->have()): ?>
                        <?php while($this->next()): ?>
                        <!-- Steam风格文章卡片 -->
                        <div class="steam-post-card">
                            <!-- 左侧封面图 -->
                            <div class="post-thumbnail">
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
                            <div class="post-content">
                                <h2 class="post-title">
                                    <a href="<?php $this->permalink() ?>" class="title-link">
                                        <?php $this->title() ?>
                                    </a>
                                </h2>
                                <div class="post-meta">
                                    <span class="meta-date"><?php $this->date('Y-m-d H:i'); ?></span>
                                    <span class="meta-author"><?php $this->author(); ?></span>
                                </div>
                                <!-- 文章摘要 -->
                                <div class="post-excerpt">
                                    <?php
                                        // 优先使用自定义摘要，否则使用自动截取
                                        $customExcerpt = $this->fields->customExcerpt;
                                        if ($customExcerpt) {
                                            echo $customExcerpt;
                                        } else {
                                            $this->excerpt(150, '...');
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <!-- 右侧按钮区 -->
                            <div class="post-actions">
                                <a href="<?php $this->permalink() ?>" class="read-more-button">
                                    阅读更多
                                </a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- 无文章状态 -->
                        <div class="empty-category">
                            <div class="empty-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <h3>该分类下暂无文章</h3>
                            <p>请稍后再来查看</p>
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

<!-- 分类页专用样式 -->
<style>
/* Steam公告风格分类页样式 */

/* 分类页头部 */
.category-header {
    padding: 20px 0;
    margin-bottom: 32px;
    border-bottom: 1px solid rgba(102, 192, 244, 0.2);
}

.category-title {
    font-size: 28px;
    font-weight: bold;
    color: #ffffff;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.category-post-count {
    font-size: 14px;
    color: #8f98a0;
}

.category-post-count strong {
    color: #66c0f4;
    font-weight: bold;
}

/* 分类文章列表 */
.category-posts-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Steam风格文章卡片 */
.steam-post-card {
    display: flex;
    align-items: center;
    background-color: rgba(26, 38, 51, 0.9);
    border: 1px solid rgba(102, 192, 244, 0.3);
    border-radius: 6px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.steam-post-card:hover {
    background-color: rgba(26, 38, 51, 0.95);
    border-color: #66c0f4;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(102, 192, 244, 0.2);
}

/* 左侧封面图 */
.post-thumbnail {
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

.steam-post-card:hover .thumbnail-image {
    transform: scale(1.05);
}

/* 中间内容区 */
.post-content {
    flex: 1;
    padding: 0 24px;
    min-width: 0;
}

.post-title {
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

.post-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: #8f98a0;
    margin-bottom: 12px;
}

.meta-date, .meta-author {
    display: inline-block;
}

/* 文章摘要 */
.post-excerpt {
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
.post-actions {
    padding: 0 24px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* 阅读更多按钮 */
.read-more-button {
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

.read-more-button:hover {
    background: linear-gradient(90deg, #4a90e2 0%, #66c0f4 100%);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(102, 192, 244, 0.3);
    color: #ffffff;
    text-decoration: none;
}

.read-more-button:active {
    transform: translateY(0);
    box-shadow: 0 1px 4px rgba(102, 192, 244, 0.3);
}

/* 无文章状态 */
.empty-category {
    text-align: center;
    padding: 80px 20px;
    background-color: rgba(26, 38, 51, 0.9);
    border: 1px solid rgba(102, 192, 244, 0.2);
    border-radius: 6px;
    margin-top: 20px;
}

.empty-icon {
    font-size: 64px;
    color: #8f98a0;
    margin-bottom: 16px;
}

.empty-category h3 {
    color: #ffffff;
    font-size: 18px;
    margin-bottom: 8px;
}

.empty-category p {
    color: #8f98a0;
    font-size: 14px;
    margin: 0;
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
    .steam-post-card {
        gap: 16px;
        padding: 12px;
    }
    
    .post-thumbnail {
        width: 150px;
        height: 100px;
    }
    
    .post-content {
        padding: 0 16px;
    }
    
    .post-actions {
        padding: 0 16px;
    }
    
    .read-more-button {
        padding: 6px 16px;
        font-size: 12px;
    }
}

@media (max-width: 768px) {
    .steam-post-card {
        flex-direction: column;
        align-items: stretch;
        height: auto;
        padding: 0;
    }
    
    .post-thumbnail {
        width: 100%;
        height: 180px;
    }
    
    .post-content {
        padding: 16px;
    }
    
    .post-actions {
        padding: 0 16px 16px;
        justify-content: flex-start;
    }
    
    .read-more-button {
        width: 100%;
        text-align: center;
    }
    
    .post-title {
        font-size: 16px;
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
    }
    
    .post-excerpt {
        -webkit-line-clamp: 3;
    }
    
    .category-title {
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .post-thumbnail {
        height: 140px;
    }
    
    .category-header {
        padding: 16px 0;
        margin-bottom: 24px;
    }
    
    .category-title {
        font-size: 20px;
    }
    
    .category-posts-list {
        gap: 12px;
    }
}
</style>
