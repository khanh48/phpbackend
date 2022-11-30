<form method="post" enctype="multipart/form-data">
    <div class="form-group p-1">
        <input type="text" class="form-control f-sm mb-1" placeholder="Tiêu đề" name="tieude" required>
        <textarea class="form-control f-sm" placeholder="Nội dung" name="noidung" required></textarea>
    </div>
    <div class="group-file">
        <select class="form-select form-select-sm" name="nhom">
            <option selected disabled value="">Groups</option>
            <option value="Bắc">Bắc</option>
            <option value="Trung">Trung</option>
            <option value="Nam">Nam</option>
        </select>
        <input type="file" id="file-1" class="inputfile inputfile-1" name="uploadImg[]"
            data-multiple-caption="{count} files selected" accept="image/*" multiple />
        <label for="file-1"> <i class="fas fa-images"></i> <span>Choose
                images&hellip;</span></label>
        <button type="submit" name="post" class="btn btn-danger btn-sm">Đăng</button>
    </div>
</form>