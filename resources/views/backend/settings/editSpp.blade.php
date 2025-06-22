<div class="modal fade" id="editSppModal" tabindex="-1" role="dialog" aria-labelledby="editSppModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSppModalLabel">Edit Setting SPP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="edit-spp-form">
    @csrf
    @method('PUT')
                    <div class="modal-body">
                    <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-kelas_id">Kelas</label>
                        <select name="kelas_id" id="edit-kelas_id" class="form-control select2" required style="width: 100%;">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-tahun_ajaran">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="edit-tahun_ajaran" class="form-control" placeholder="Contoh: 2024/2025" required />
                    </div>
                    <div class="form-group">
                        <label for="edit-bulan">Bulan (Opsional)</label>
                        <input type="text" name="bulan" id="edit-bulan" class="form-control" placeholder="Contoh: Juli (Kosongkan jika berlaku semua bulan)" />
                    </div>
                    <div class="form-group">
                        <label for="edit-amount">Biaya SPP</label>
                        <input type="number" name="amount" id="edit-amount" class="form-control" placeholder="Contoh: 150000" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>