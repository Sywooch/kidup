<!-- Search modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Modal header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Choose a filter</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Modal body
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="restoreFilterModal()" data-dismiss="modal">Close</button>
                <button type="button" class="btnBack btn btn-default hidden" onclick="restoreFilterModal()">Back</button>
                <button type="button" class="btnApply btn btn-danger hidden" onclick="applyFilter()">Apply filter</button>
            </div>

        </div>
    </div>
</div>
