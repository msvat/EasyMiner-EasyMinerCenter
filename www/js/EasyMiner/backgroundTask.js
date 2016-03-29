/**
 * @class BackgroundTask - javascriptová komponenta pro spouštění background requestů pro dlouhotrvající úlohy
 * @author Stanislav Vojíř
 * @param {Object} [params={}]
 * @constructor
 */
var BackgroundTask = function(params){
  var url=params.url;
  var sleepInterval = params.sleep ? params.sleep : 500;
  var messageTarget = params.messageTarget;
  var self=this;

  var sendTaskRequest = function(){
    console.log('function sendTaskRequest');
    jQuery.getJSON(
      url,
      function(data){
        if (data!=undefined){
          $(messageTarget).html(data.message);
          if (data.redirect!=undefined && data.redirect!=''){
            location.href=data.redirect;
            return;
          }
        }
        setTimeout(function(){
          sendTaskRequest();
        }, sleepInterval);
      }
    )
      .fail(function(data){
        $(messageTarget).html('ERROR: '+data.responseText);
      });
  };

  /**
   * Funkce pro spuštění načítání...
   */
  this.run = function(){
    sendTaskRequest(url);
  };

};

