$(function () {
  const container = document.querySelector('.task-area');
  if (!container) return;

  function positioning() {
    const currentCard = document.getElementById('display-task');

    if (!currentCard) return;

    if (container && currentCard) {
      setTimeout(() => {
        const offsetLeft = currentCard.offsetLeft - (container.clientWidth - currentCard.clientWidth) / 2;
        container.scrollLeft = offsetLeft;
      }, 0);
    }
  }

  //初期位置の設定
  positioning();

  //スワイプが止まった時の判定
  container.addEventListener('scrollend', () => {
    const scrollLeft = container.scrollLeft;
    const containerWidth = container.clientWidth;
    const scrollWidth = container.scrollWidth;

    //左（前日）へ振り切ったか判定
    if (scrollLeft <= 10) {
      const prevUrl = document.querySelector('.left-arrow').href;
      window.location.href = prevUrl;
    }
    //右（翌日）へ振り切ったか判定
    else if (scrollLeft + containerWidth >= scrollWidth - 10) {
      const nextLink = document.querySelector('.right-arrow');
      
      if (nextLink) {
        window.location.href = nextLink.href;
      } else {
        positioning();
      }
    }
  });
});