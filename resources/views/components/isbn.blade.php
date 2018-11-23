<div class="input-group" data-id="{{ $book->id }}" style="width: 100%">
    <input maxlength="17" class="for_isbn_input form-control {{ $isbn_class_add }}" style="font-size: {{ $font_size }}px" value="{{ $book->isbn?convert_isbn($book->isbn):'978-7-' }}" />
    <a class="btn btn-danger input-group-addon add_isbn">保存</a>
</div>