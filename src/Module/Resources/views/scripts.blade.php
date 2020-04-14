<script>
  var blogPage = {{ $page }};
  var blogPerPage = {{ $perPage }};
  var blogLoadMoreButton = document.querySelector('.blog__load-more');
  var blogSpinner = blogLoadMoreButton.querySelector('.spinner');
  var blogArticleHolder = document.querySelector('{{ $holder }}');

  var blogStopSpinner = function() {
    blogSpinner.classList.add('spinner--no-loading');
  };
  var blogStartSpinner = function() {
    blogSpinner.classList.remove('spinner--no-loading');
  };

  var blogLoadMoreClicked = function(event) {
    event.preventDefault();
    blogPage += 1;

    blogStartSpinner();
    var query = [
      `page=${blogPage}`,
      `perPage=${blogPerPage}`,
    ];

@if ($templateVariables && sizeof($templateVariables))
  @php $count = 0 @endphp
  @foreach ($templateVariables as $index => $variable)
query.push(`templateVariables[{{ $count }}]={!! urlencode($index.':'.$variable) !!}`);
    @php $count ++ @endphp
  @endforeach
@endif

    axios
      .post(`{{ route('refined.blog.get-for-front') }}?${query.join('&')}`)
      .then(function(response) {
        var responseData = response.data;
        blogStopSpinner();
        if (responseData.done) {
          blogLoadMoreButton.remove();
        }

        if (responseData.items) {
          responseData.items.forEach(item => {
            blogArticleHolder.insertAdjacentHTML('beforeend', item);
          })
        }

      })
      .catch(function(error) {
        alert('Failed to load next set');
        blogPage -= 1;
        console.warn(error.message);
        blogStopSpinner();
      });
  };
  blogLoadMoreButton.addEventListener('click', blogLoadMoreClicked)
</script>
