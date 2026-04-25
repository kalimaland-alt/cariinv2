<?php /** @var array $chat */ /** @var array $meta */ /** @var array $messages */ /** @var int $userId */ ?>
<div class="container-fluid p-4">
    <a href="<?= site_url('/chat') ?>" class="btn btn-link p-0 mb-2"><i class="bi bi-arrow-left"></i> Kembali ke Pesan</a>

    <div class="card card-soft">
        <div class="card-header d-flex align-items-center gap-3 py-2">
            <img src="<?= esc(property_image_url($meta['cover_image'] ?? null)) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
            <div class="flex-grow-1">
                <div class="fw-bold small"><a href="<?= site_url('/property/'.$meta['property_slug']) ?>" target="_blank"><?= esc($meta['property_title']) ?></a></div>
                <small class="text-muted"><?= esc($meta['buyer_name']) ?> ↔ <?= esc($meta['seller_name']) ?></small>
            </div>
        </div>

        <div class="p-3" id="chatBox" style="height: 460px; overflow-y: auto; background: var(--mint-50);">
            <?php if (empty($messages)): ?>
                <div class="text-center text-muted my-5">Belum ada pesan. Mulai percakapan!</div>
            <?php endif; ?>
            <?php foreach ($messages as $m): ?>
                <?php $isMe = (int)$m['sender_id'] === $userId; ?>
                <div class="d-flex mb-2 <?= $isMe ? 'justify-content-end' : 'justify-content-start' ?>">
                    <div style="max-width:70%;">
                        <div class="<?= $isMe ? 'bg-success text-white' : 'bg-white' ?> px-3 py-2 rounded-3 shadow-sm" style="white-space: pre-wrap;">
                            <?= esc($m['message']) ?>
                        </div>
                        <small class="text-muted d-block mt-1 <?= $isMe?'text-end':'' ?>"><?= date('d M H:i', strtotime($m['created_at'])) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <form action="<?= site_url('/chat/send/'.$chat['id']) ?>" method="post" class="d-flex gap-2 p-3 border-top">
            <?= csrf_field() ?>
            <input type="text" name="message" class="form-control" placeholder="Tulis pesan..." required autofocus>
            <button class="btn btn-success"><i class="bi bi-send"></i></button>
        </form>
    </div>
</div>
<script>
const box = document.getElementById('chatBox');
if (box) box.scrollTop = box.scrollHeight;
</script>
