<?php
/**
 * 404错误页模板
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
