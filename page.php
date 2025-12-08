<?php
/**
 * 独立页面模板
 * 
 * @package SteamAnnouncement
 */

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
            
            // 输出配置的菜单项
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
                    echo '<li class="nav-item"><span class="nav-group-title">' . htmlspecialchars($item['name']) . '</span></li>';
                } else {
                    // 普通菜单项
                    echo '<li class="nav-item"><a href="' . htmlspecialchars($item['url']) . '" class="nav-link' . ($isActive ? ' active' : '') . '">' . htmlspecialchars($item['name']) . '</a></li>';
                }
            }
            
            // 输出Typecho页面列表 - 修复显示URL而非标题的bug
            $this->widget('Widget_Contents_Page_List')->to($pages);
            if ($pages->have()) {
                echo '<li class="nav-item"><span class="nav-group-title">页面</span></li>';
                while($pages->next()):
                    // 检查当前页面是否为活跃状态
                    $isActive = ($this->is('page') && $this->cid == $pages->cid);
                    // 获取页面标题和链接
                    $pageTitle = $pages->title;
                    $pagePermalink = $pages->permalink;
                    // 确保标题和链接是字符串类型
                    if (!is_string($pageTitle)) {
                        $pageTitle = (string)$pageTitle;
                    }
                    if (!is_string($pagePermalink)) {
                        $pagePermalink = (string)$pagePermalink;
                    }
                    // 输出转义后的HTML
                    echo '<li class="nav-item"><a href="' . htmlspecialchars($pagePermalink) . '" class="nav-link' . ($isActive ? ' active' : '') . '">' . htmlspecialchars($pageTitle) . '</a></li>';
                endwhile;
            }
            ?>
        </ul>
    </nav>
    
    <!-- 主内容区 -->
    <div class="content-container">
        <main class="main-content">
            <div class="container">
                <article class="single-post">
                    <!-- 页面标题和元数据 -->
                    <header class="post-header">
                        <h1 class="post-title"><?php $this->title(); ?></h1>
                        <div class="post-meta">
                            <span class="post-meta-item">
                                <i class="far fa-calendar"></i>
                                <?php $this->date('Y-m-d H:i'); ?>
                            </span>
                            <span class="post-meta-item">
                                <i class="far fa-user"></i>
                                <?php $this->author(); ?>
                            </span>
                        </div>
                    </header>
                    
                    <!-- 页面内容 -->
                    <div class="post-content">
                        <?php $this->content(); ?>
                    </div>
                    
                    <!-- 页面底部 -->
                    <footer class="post-footer">
                        <!-- 可以添加页面底部信息 -->
                    </footer>
                </article>
                
                <!-- 评论区 -->
                <?php if (!isset($this->options->commentsDisabled) || !$this->options->commentsDisabled): ?>
                <div class="comments-section">
                    <?php $this->comments(); ?>
                    <?php $this->commentForm(); ?>
                </div>
                <?php endif; ?>
            </div>
        </main>
        
        <?php $this->need('footer.php'); ?>
    </div>
</div>
