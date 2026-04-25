<?php /** @var array $rows */ /** @var int $userId */ ?>
<div class="container-fluid p-4">
    <h4 class="mb-3">Pesan Saya</h4>
    <?php if (empty($rows)): ?>
        <div class="card card-soft p-5 text-center">
            <i class="bi bi-chat-dots display-4 text-muted"></i>
            <h5 class="mt-3">Belum ada percakapan</h5>
            <p class="text-muted">Mulai chat dengan klik tombol "Chat" pada halaman detail properti.</p>
        </div>
    <?php else: ?>
        <div class="card card-soft">
            <div class="list-group list-group-flush">
                <?php foreach ($rows as $r): ?>
                    <?php $isBuyer = (int)$r['buyer_id'] === $userId; $other = $isBuyer ? $r['seller_name'] : $r['buyer_name']; $otherAvatar = $isBuyer ? $r['seller_avatar'] : $r['buyer_avatar']; ?>
                    <a href="<?= site_url('/chat/thread/'.$r['id']) ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                        <img src="<?= esc(property_image_url($r['cover_image'])) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                        <div class="flex-grow-1">
                            <div class="fw-bold small"><?= esc($r['property_title']) ?></div>
                            <div class="small">
                                <?php if (! empty($otherAvatar)): ?><img src="<?= esc($otherAvatar) ?>" style="width:18px;height:18px;border-radius:50%;"><?php endif; ?>
                                <span><?= esc($other) ?></span>
                            </div>
                            <div class="small text-muted text-truncate" style="max-width:400px;"><?= esc($r['last_message'] ?? '— belum ada pesan —') ?></div>
                        </div>
                        <small class="text-muted"><?= $r['last_message_at'] ? date('d M H:i', strtotime($r['last_message_at'])) : '' ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
