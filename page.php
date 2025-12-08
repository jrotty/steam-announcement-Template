<?php
/**
 * 独立页面模板
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
                <article class="single-post">
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
                    
                    <div class="post-content">
                        <?php $this->content(); ?>
                    </div>
                </article>
                
                <!-- 评论区 -->
                <div class="comments-section">
                    <?php $this->comments(); ?>
                    <?php $this->commentForm(); ?>
                </div>
            </div>
        </main>
        
        <?php $this->need('footer.php'); ?>
    </div>
</div>
