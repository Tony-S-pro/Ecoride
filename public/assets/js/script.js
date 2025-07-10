(function () {

      const tables = document.querySelectorAll('table');

      tables.forEach((table) => {
        new Tabled({table: table,  failClass: 'tabled--stacked'});
      });
    })();