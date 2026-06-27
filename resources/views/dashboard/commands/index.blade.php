@extends('dashboard.layout.master')

@section('dashboard-main')
<div class="row pt-4 px-4">
    <div class="col-12">
        <div class="card bg-dark text-white shadow-lg border-0">
            <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center bg-transparent py-3">
                <h5 class="mb-0 text-white"><i class="ti tabler-terminal me-2"></i>{{ __('admin.server_commands') ?? 'Terminal' }}</h5>
                <span class="badge bg-danger px-3 py-2">Super Admin Only</span>
            </div>
            <div class="card-body py-4">
                <div class="row mb-4">
                    <div class="col-md-9 text-start">
                        <div class="input-group mb-3 shadow-sm">
                            <select id="commandType" class="form-select bg-dark text-white border-secondary" style="max-width: 120px;">
                                <option value="artisan">Artisan</option>
                                <option value="shell">Shell</option>
                            </select>
                            <input type="text" id="commandInput" class="form-control bg-dark text-white border-secondary font-monospace" placeholder="Enter command... (e.g. migrate --force)">
                            <button id="runBtn" class="btn btn-primary px-4" type="button"><i class="ti tabler-player-play me-1"></i> Run</button>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button id="syncPermsBtn" class="btn btn-warning btn-sm shadow-sm" type="button">
                                <i class="ti tabler-shield-lock me-1"></i> Clean & Sync Permissions
                            </button>
                            <small class="text-white-50 align-self-center fst-italic">Tip: For artisan commands, don't include "php artisan".</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-end pt-2">
                        <button id="clearBtn" class="btn btn-outline-light btn-sm" type="button"><i class="ti tabler-trash me-1"></i> Clear Terminal</button>
                    </div>
                </div>

                <div id="outputArea" class="bg-black p-4 rounded-3 border border-secondary font-monospace shadow-inner" style="min-height: 450px; max-height: 600px; overflow-y: auto; color: #00ff41; white-space: pre-wrap; font-size: 14px; line-height: 1.5; box-shadow: inset 0 0 10px rgba(0,0,0,1);">
$ Waiting for input...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('dashboard-footer')
<script>
    $(document).ready(function() {
        const $output = $('#outputArea');
        const $input = $('#commandInput');
        const $btn = $('#runBtn');
        const $type = $('#commandType');

        function appendOutput(text, type = 'info') {
            const time = new Date().toLocaleTimeString();
            let color = '#fff';
            if (type === 'error') color = '#ff5555';
            if (type === 'success') color = '#00ff41';
            if (type === 'command') color = '#ffff55';

            const formattedText = `<div class="mb-2" style="color: ${color}"><span class="text-muted">[${time}]</span> ${text}</div>`;
            $output.append(formattedText);
            $output.scrollTop($output[0].scrollHeight);
        }

        $btn.on('click', function() {
            const command = $input.val().trim();
            const type = $type.val();

            if (!command) return;

            appendOutput(`$ Running ${type} command: ${command}`, 'command');
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Running...');

            $.ajax({
                url: "{{ route('admin.commands.execute') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    command: command,
                    type: type
                },
                success: function(response) {
                    appendOutput(response.output, 'success');
                },
                error: function(xhr) {
                    const error = xhr.responseJSON ? xhr.responseJSON.output : 'Unknown error occurred.';
                    appendOutput(error, 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="ti tabler-player-play me-1"></i> Run');
                    $input.val('');
                }
            });
        });

        $('#syncPermsBtn').on('click', function() {
            appendOutput(`$ Triggering Fresh Sync (Cleaning duplicates and re-seeding)...`, 'command');
            $(this).prop('disabled', true);

            $.ajax({
                url: "{{ route('admin.sync-permissions') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    appendOutput(response.output, 'success');
                },
                error: function(xhr) {
                    const error = xhr.responseJSON ? xhr.responseJSON.output : 'Unknown error occurred.';
                    appendOutput(error, 'error');
                },
                complete: function() {
                    $('#syncPermsBtn').prop('disabled', false);
                }
            });
        });

        $input.on('keypress', function(e) {
            if (e.which == 13) {
                $btn.click();
            }
        });

        $('#clearBtn').on('click', function() {
            $output.html('<div style="color: #fff-50">$ Terminal cleared.</div>');
        });
    });
</script>
@endsection
