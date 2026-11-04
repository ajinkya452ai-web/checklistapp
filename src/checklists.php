<?php
function ensureMonth(PDO $db,int $userId,string $month): int {
    if (!validMonth($month)) throw new InvalidArgumentException('Invalid month.');
    $db->beginTransaction();
    try {
        $q=$db->prepare('INSERT INTO monthly_checklists(user_id,month_key) VALUES(?,?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)');
        $q->execute([$userId,$month]);
        $id=(int)$db->lastInsertId();
        // Lock the parent row, so concurrent first opens cannot duplicate items.
        $q=$db->prepare('SELECT id FROM monthly_checklists WHERE id=? AND user_id=? FOR UPDATE');
        $q->execute([$id,$userId]);
        if (!$q->fetch()) throw new RuntimeException('Checklist not found');
        $q=$db->prepare('SELECT COUNT(*) FROM monthly_checklist_items WHERE checklist_id=?');$q->execute([$id]);
        // Empty month still needs initialization marker: use parent initialized_at.
        $q=$db->prepare('SELECT initialized_at FROM monthly_checklists WHERE id=?');$q->execute([$id]);
        if ($q->fetchColumn()===null) {
            $q=$db->prepare('INSERT INTO monthly_checklist_items(checklist_id,template_id,title,is_extra,position) SELECT ?,id,title,0,position FROM checklist_templates WHERE user_id=? AND active=1 ORDER BY position,id');
            $q->execute([$id,$userId]);
            $q=$db->prepare('UPDATE monthly_checklists SET initialized_at=NOW() WHERE id=?');$q->execute([$id]);
        }
        $db->commit();return $id;
    } catch(Throwable $e) {$db->rollBack();throw $e;}
}
function ownedItem(PDO $db,int $item,int $user,string $month): bool {
    $q=$db->prepare('SELECT i.id FROM monthly_checklist_items i JOIN monthly_checklists m ON m.id=i.checklist_id WHERE i.id=? AND m.user_id=? AND m.month_key=?');
    $q->execute([$item,$user,$month]);return (bool)$q->fetch();
}
