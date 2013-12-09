<?php
/**
 * The view file of thread module of chanzhiEPS.
 *
 * @copyright   Copyright 2013-2013 青岛息壤网络信息有限公司 (QingDao XiRang Network Infomation Co,LTD www.xirangit.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     thread
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
include '../../common/view/header.html.php';
include '../../common/view/kindeditor.html.php';

$common->printPositionBar($board, $thread);

if($pager->pageID == 1) include './thread.html.php';
if(!$thread->readonly)  include './reply.html.php';
else echo "<div class='alert alert-info'>该帖已设置为只读，无法进行回复。</div>";

include '../../common/view/footer.html.php';
