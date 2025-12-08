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
                <!-- 搜索/归档页头部 - Steam风格 -->
                <div class="search-header">
                    <h1 class="search-title">
                        <?php $this->archiveTitle(array('category' => '%s'), '', ''); ?>
                    </h1>
                    <div class="search-info">
                        <p class="search-results-count">
                            共包含 <strong><?php $this->archiveCount(); ?></strong> 篇相关文章
                        </p>
                    </div>
                </div>
                
                <!-- 搜索结果列表 - Steam风格 -->
                <div class="search-results">
                    <?php if ($this->have()): ?>
                        <?php while($this->next()): ?>
                        <article class="search-result-card">
                            <div class="search-result-content">
                                <!-- 搜索结果标题 -->
                                <h2 class="search-result-title">
                                    <a href="<?php $this->permalink() ?>" title="<?php $this->title() ?>"><?php $this->title() ?></a>
                                </h2>
                                
                                <!-- 搜索结果摘要 -->
                                <div class="search-result-excerpt">
                                    <?php
                                        // 优先使用自定义摘要，否则使用自动截取
                                        $customExcerpt = $this->fields->customExcerpt;
                                        if ($customExcerpt) {
                                            echo $customExcerpt;
                                        } else {
                                            $this->excerpt(200, '...');
                                        }
                                    ?>
                                </div>
                                
                                <!-- 搜索结果元数据 -->
                                <div class="search-result-meta">
                                    <span class="search-result-date">
                                        <i class="far fa-calendar"></i>
                                        <?php $this->date('Y-m-d H:i'); ?>
                                    </span>
                                    <span class="search-result-author">
                                        <i class="far fa-user"></i>
                                        <?php $this->author(); ?>
                                    </span>
                                    <span class="search-result-category">
                                        <i class="far fa-folder"></i>
                                        <?php $this->category(', '); ?>
                                    </span>
                                    <span class="search-result-comments">
                                        <i class="far fa-comment"></i>
                                        <?php $this->commentsNum('0', '1', '%d'); ?>
                                    </span>
                                </div>
                                
                                <!-- 搜索结果标签 -->
                                <div class="search-result-tags">
                                    <?php $this->tags(' ', true, '无标签'); ?>
                                </div>
                                
                                <!-- 阅读更多按钮 -->
                                <div class="announcement-buttons">
                                    <a href="<?php $this->permalink() ?>" class="btn">阅读更多</a>
                                </div>
                            </div>
                        </article>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- 无搜索结果状态 -->
                        <div class="no-search-results">
                            <i class="fas fa-folder-open no-results-icon"></i>
                            <h3>该分类下暂无文章</h3>
                            <p>该分类下还没有发布任何文章</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- 分页样式 - Steam风格 -->
                <div class="pagination">
                    <?php $this->pageNav('上一页', '下一页', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'page-nav', 'itemTag' => 'span', 'textTag' => 'a', 'currentClass' => 'current')); ?>
                </div>
            </div>
        </main>
        
        <?php $this->need('footer.php'); ?>
    </div>
</div>
