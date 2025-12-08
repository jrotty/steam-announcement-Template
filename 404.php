<?php
/**
 * 404错误页模板
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
            
            // 显示Typecho页面 - 修复显示URL而非标题的bug
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
                <div class="error-404">
                    <div class="error-code">404</div>
                    <h1 class="error-title">页面未找到</h1>
                    <p class="error-description">
                        抱歉，您访问的页面不存在或已被删除。
                    </p>
                    <div class="error-actions">
                        <a href="<?php $this->options->siteUrl(); ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i>
                            返回首页
                        </a>
                        <a href="javascript:history.back()" class="btn">
                            <i class="fas fa-arrow-left"></i>
                            返回上一页
                        </a>
                    </div>
                    
                    <div class="error-search">
                        <h3>搜索一下？</h3>
                        <form method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                            <input type="text" name="s" placeholder="搜索文章..." class="search-input">
                            <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        
        <?php $this->need('footer.php'); ?>
    </div>
</div>
