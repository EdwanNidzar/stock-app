<!-- resources/views/layouts/history.blade.php -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyModalLabel">History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="loadingSpinner" class="text-center" style="display: none;">
          <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        <div id="historyContent" class="row">
          <!-- History content will be loaded here dynamically -->
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Handle button click to load history data based on table name
  document.addEventListener('DOMContentLoaded', function() {
    const viewHistoryBtn = document.getElementById('viewHistoryBtn');
    if (viewHistoryBtn) {
      viewHistoryBtn.addEventListener('click', function() {
        const tableName = this.getAttribute('data-table');
        const historyModal = $('#historyModal');
        const loadingSpinner = $('#loadingSpinner');
        const historyContent = $('#historyContent');

        // Show loading spinner
        loadingSpinner.show();
        historyContent.hide();

        // Fetch history data via Ajax
        $.ajax({
          url: '/history/' + tableName, // Route to fetch history by table
          method: 'GET',
          success: function(response) {
            historyContent.empty(); // Clear previous content

            // Loop through the history data and append to modal content
            response.forEach(function(item) {
              // Format the data more neatly
              const formatData = (data) => {
                if (typeof data === 'object' && data !== null) {
                  let formatted = '<ul class="list-disc pl-6">';
                  for (let key in data) {
                    formatted += `<li><strong>${key}:</strong> ${data[key]}</li>`;
                  }
                  formatted += '</ul>';
                  return formatted;
                }
                return data || 'N/A';
              };

              const historyCard = `
                <div class="col-md-12 mb-3">
                  <div class="card shadow-sm w-full">
                    <div class="card-body">
                      <h5 class="card-title font-semibold text-lg">${item.user.name}</h5>
                      <p class="card-text"><strong>Action:</strong> ${item.action}</p>
                      <p class="card-text"><strong>Old Data:</strong> ${formatData(item.old_data)}</p>
                      <p class="card-text"><strong>New Data:</strong> ${formatData(item.new_data)}</p>
                      <p class="card-text"><strong>Timestamp:</strong> ${item.created_at}</p>
                    </div>
                  </div>
                </div>
              `;
              historyContent.append(historyCard);
            });

            // Hide loading spinner and show content
            loadingSpinner.hide();
            historyContent.show();

            // Open the modal
            historyModal.modal('show');
          },
          error: function() {
            alert('Error fetching history.');
            loadingSpinner.hide();
          }
        });
      });
    }
  });
</script>
