<section class="page-header">
    <div>
        <p class="page-eyebrow">Document Forward Request Module</p>
        <h1 class="page-title">Request a Document</h1>
        <p class="page-subtitle">Submit a document request and verify it in the system.</p>
    </div>
</section>

<section class="card request-card">
    <form method="post" action="<?= url('/request') ?>" enctype="multipart/form-data" class="request-form">
        <div class="field">
            <label for="document">Document Type <span class="required">*</span></label>
            <select id="document" name="document" required>
                <option value="">Select Document Type</option>
                <?php foreach ($documents as $document): ?>
                    <option value="<?= e($document['name']) ?>"><?= e($document['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="purpose">Purpose <span class="required">*</span></label>
            <textarea id="purpose" name="purpose" rows="4" placeholder="Enter purpose..." required></textarea>
        </div>

        <div class="field">
            <label for="notes">Additional Notes <span class="optional">(Optional)</span></label>
            <textarea id="notes" name="notes" rows="3" placeholder="Any additional details..."></textarea>
        </div>

        <div class="field">
            <label for="attachment">Supporting File <span class="optional">(Optional)</span></label>
            <input type="file" id="attachment" name="attachment"
                   accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
            <p class="file-hint">PDF, DOC, DOCX, PNG, JPG, or JPEG &middot; Max 5 MB</p>
        </div>

        <div class="form-actions">
            <a class="btn-secondary" href="<?= url('/dashboard') ?>">Cancel</a>
            <button type="submit" class="btn">Submit Request</button>
        </div>
    </form>
</section>
