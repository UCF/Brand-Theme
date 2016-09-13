angular.module('UIDSearch', []);
angular.module('UIDSearch').service('UIDSearchService', UIDSearchService);
angular.module('UIDSearch').controller('UIDSearchController', UIDSearchController);
angular.module('UIDSearch').filter('replaceAll', replaceAllFilter);

UIDSearchService.$inject = ['$http'];
function UIDSearchService($http) {
    var URL = CONFIG.BASE_URL + "/wp-json/rest/uids?s=";

    var getUids = function (callback, query) {
        $http({
            url: URL,
            method: "GET",
            params: { 's': query }
        }).success(function (data, status, headers, config) {
            callback(data);
        }).error(function (data, status, headers, config) {
            callback({ "error": true });
        });
    };

    return {
        getUids: getUids
    };
}

UIDSearchController.$inject = ['$scope', 'UIDSearchService'];
function UIDSearchController($scope, UIDSearchService) {
    var ctrl = this;

    ctrl.searchQuery = { term: '' };
    ctrl.results = [];
    ctrl.error = false;
    ctrl.loading = false;
    ctrl.noResults = false;

    function setUids(data) {
        ctrl.loading = false;
        if (data === null) {
            ctrl.noResults = true;
        } else if (data.error && data.error === true) {
            ctrl.error = true;
        }
        ctrl.results = data;
        setTimeout(function () {
            $(".uid-result-container").find("h3").matchHeight();
        }, 500);
        $(".request-form").fadeIn();
    }

    $scope.$watch(angular.bind(ctrl, function () {
        return ctrl.searchQuery.term;
    }), function (newValue) {
        ctrl.noResults = false;
        if (ctrl.searchQuery.term.length > 2) {
            ctrl.error = false;
            ctrl.loading = true;
            UIDSearchService.getUids(setUids, ctrl.searchQuery.term);
        } else {
            ctrl.results = [];
        }
    });

}

function replaceAllFilter() {
    return function (input, needle) {
        return input.replace(new RegExp(needle, "g"), '');
    };
}
