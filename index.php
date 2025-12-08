<?php
/**
 * SteamAnnouncement
 * 
 * @package SteamAnnouncement
 * @author hatch_blod
 * @version 0.1
 * @link https://blog.002.hk
 */

/**
 * 解析侧边导航菜单配置
 * 
 * @param string $menuConfig 菜单配置文本
 * @return array 解析后的菜单数组
 */
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
                <!-- 公告栏 - 1号标记位置 -->
                <?php if ($this->options->announcement): ?>
                <div class="announcement-bar">
                    <?php
                        $announcementContent = $this->options->announcement;
                        $announcementLines = explode("\n", $announcementContent);
                    ?>
                    <?php foreach ($announcementLines as $line): ?>
                        <?php if (trim($line)): ?>
                            <h3 class="announcement-item">
                                <i class="fas fa-bullhorn"></i>
                                <?php echo trim($line); ?>
                            </h3>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div class="announcement-list">
                    <?php while($this->next()): ?>
                    <article class="announcement-card">
                        <!-- 公告缩略图 -->
                        <div class="announcement-thumbnail">
                            <?php
                                // 获取文章封面图，默认使用占位图
                                $thumbnail = $this->fields->thumbnail;
                                $thumbnailUrl = $thumbnail ? $thumbnail : 'https://via.placeholder.com/240x135';
                            ?>
                            <img src="<?php echo $thumbnailUrl; ?>" alt="<?php $this->title() ?>">
                        </div>
                        
                        <!-- 公告内容 -->
                        <div class="announcement-content">
                            <h2 class="announcement-title">
                                <a href="<?php $this->permalink() ?>" title="<?php $this->title() ?>"><?php $this->title() ?></a>
                            </h2>
                            
                            <!-- 公告标签 -->
                            <div class="announcement-tags">
                                <?php $this->tags('<span class="announcement-tag">', '</span>', ''); ?>
                            </div>
                            
                            <!-- 公告摘要 -->
                            <div class="announcement-excerpt">
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
                            
                            <!-- 公告元数据 -->
                            <div class="announcement-meta">
                                <span class="announcement-date"><?php $this->date('Y-m-d H:i'); ?></span>
                                <span class="announcement-author">by <?php $this->author(); ?></span>
                                
                                <!-- 互动数据 -->
                                <div class="announcement-actions">
                                    <span class="action-item">
                                        <i class="fas fa-comment"></i>
                                        <?php $this->commentsNum('0', '1', '%d'); ?>
                                    </span>
                                    <span class="action-item">
                                        <i class="fas fa-thumbs-up"></i>
                                        0
                                    </span>
                                </div>
                            </div>
                            
                            <!-- 公告按钮 -->
                        <div class="announcement-buttons">
                            <a href="<?php $this->permalink() ?>" class="btn btn-secondary">阅读更多</a>
                        </div>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
                
                <div class="pagination">
                    <?php $this->pageNav('上一页', '下一页', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'page-nav', 'itemTag' => 'span', 'textTag' => 'a', 'currentClass' => 'current')); ?>
                </div>
            </div>
        </main>
        
        <?php $this->need('footer.php'); ?>
    </div>
</div>
