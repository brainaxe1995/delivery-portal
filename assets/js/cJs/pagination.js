// pagination.js — generic pager for any fetchXxx(page) function

// pick up whichever fetcher you have
// pagination.js — now detects all your page-specific fetchers
const fetcher =
     (typeof fetchNewOrders        === 'function' ? fetchNewOrders       :
     (typeof fetchCancelledOrders === 'function' ? fetchCancelledOrders :
     (typeof fetchPendingOrders    === 'function' ? fetchPendingOrders   :
     (typeof fetchInTransitOrders  === 'function' ? fetchInTransitOrders :
     (typeof fetchDeliveredOrders  === 'function' ? fetchDeliveredOrders :
      null)))));

if (!fetcher) {
  console.warn('⚠️ pagination.js: no fetchNewOrders, fetchCancelledOrders, fetchPendingOrders, fetchInTransitOrders or fetchDeliveredOrders found');
}


function buildPagination() {
  const $ul = $('.base-pagination.pagination').empty();
  const maxVisible = 5;

  // Prev
  $ul.append(`
    <li class="page-item ${currentPage<=1?'disabled':''}">
      <a class="page-link" href="#" data-page="${currentPage-1}">
        <i class="lni lni-angle-double-left"></i>
      </a>
    </li>`);

  // few pages?
  if (totalPages <= maxVisible + 2) {
    for (let p=1;p<=totalPages;p++){
      $ul.append(`
        <li class="page-item ${p===currentPage?'active':''}">
          <a class="page-link" href="#" data-page="${p}">${p}</a>
        </li>`);
    }
  } else {
    // first
    $ul.append(`
      <li class="page-item ${currentPage===1?'active':''}">
        <a class="page-link" href="#" data-page="1">1</a>
      </li>`);
    let start = Math.max(2, currentPage-1),
        end   = Math.min(totalPages-1, currentPage+1);

    if (currentPage <= Math.ceil(maxVisible/2)+1) {
      start = 2; end = maxVisible;
    } else if (currentPage >= totalPages - Math.floor(maxVisible/2)) {
      end   = totalPages-1;
      start = totalPages-maxVisible+1;
    }

    if (start>2) {
      $ul.append(`<li class="page-item disabled"><span class="page-link">…</span></li>`);
    }

    for (let p=start; p<=end; p++){
      $ul.append(`
        <li class="page-item ${p===currentPage?'active':''}">
          <a class="page-link" href="#" data-page="${p}">${p}</a>
        </li>`);
    }

    if (end<totalPages-1) {
      $ul.append(`<li class="page-item disabled"><span class="page-link">…</span></li>`);
    }

    // last
    $ul.append(`
      <li class="page-item ${currentPage===totalPages?'active':''}">
        <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
      </li>`);
  }

  // Next
  $ul.append(`
    <li class="page-item ${currentPage>=totalPages?'disabled':''}">
      <a class="page-link" href="#" data-page="${currentPage+1}">
        <i class="lni lni-angle-double-right"></i>
      </a>
    </li>`);

  // bind clicks
  $ul.find('a.page-link').off('click').on('click',function(e){
    e.preventDefault();
    const np = parseInt($(this).data('page'),10);
    if (!isNaN(np) && np>=1 && np<=totalPages && fetcher) {
      fetcher(np);
    }
  });
}
