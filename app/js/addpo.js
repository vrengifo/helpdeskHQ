        function addPO (field,total,counter) {
              var i;
              total=0;
              while (i<=counter) {
                   i++;
                   total+=(parentt.mainform.price&i) * (parent.mainform.itemqty&i);
              };
        }