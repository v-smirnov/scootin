package infrastructure_test

import (
	"io"
	"net/http"
	"net/http/httptest"
	"testing"

	"github.com/stretchr/testify/assert"
	"github.com/v-smirnov/scootin/app/application"
	"github.com/v-smirnov/scootin/app/domain"
	"github.com/v-smirnov/scootin/app/infrastructure"
)

func TestGetVehicles(t *testing.T) {
	cases := []struct {
		name             string
		apiKey           string
		expectedResponse *application.GetVehiclesResponse
		isErrorExpected  bool
	}{
		{
			name:   "successful response, some vehicles found",
			apiKey: "ok",
			expectedResponse: &application.GetVehiclesResponse{
				Vehicles: []application.Vehicle{
					{
						VehicleIdentifier: "identifier_1",
						VehicleType:       "scooter",
					},
				},
			},
			isErrorExpected: false,
		},
		{
			name:   "successful response, no vehicles found",
			apiKey: "empty",
			expectedResponse: &application.GetVehiclesResponse{
				Vehicles: []application.Vehicle{},
			},
			isErrorExpected: false,
		},
		{
			name:   "failed scenario, broken json",
			apiKey: "broken_json",
			expectedResponse: nil,
			isErrorExpected: true,
		},
		{
			name:   "failed scenario, invalid request",
			apiKey: "invalid_request",
			expectedResponse: nil,
			isErrorExpected: true,
		},
		{
			name:   "failed scenario, server error",
			apiKey: "server_error",
			expectedResponse: nil,
			isErrorExpected: true,
		},
	}

	testServer := httptest.NewServer(http.HandlerFunc(getVehiclesDummy))
	defer testServer.Close()

	for _, testCase := range cases {
		apiClient := infrastructure.NewApiClient(testServer.URL, testCase.apiKey)
		resp, err := apiClient.GetVehicles(createGetVehiclesRequest())

		if testCase.isErrorExpected {
			assert.Nil(t, resp)
			assert.NotNil(t, err)
		} else {
			assert.Equal(t, testCase.expectedResponse, resp)
		}
	}
}

func getVehiclesDummy(w http.ResponseWriter, r *http.Request) {
	key := r.Header.Get("API-KEY")
	switch key {
	case "ok":
		w.WriteHeader(http.StatusOK)
		io.WriteString(w, "{\"vehicles\":[{\"identifier\": \"identifier_1\", \"type\": \"scooter\"}]}")
	case "empty":
		w.WriteHeader(http.StatusOK)
		io.WriteString(w, "{\"vehicles\":[]}")
	case "broken_json":
		w.WriteHeader(http.StatusOK)
		io.WriteString(w, "{\"vehicles\":[tuytu")
	case "invalid_request":
		w.WriteHeader(http.StatusBadRequest)
	case "server_error":
		w.WriteHeader(http.StatusInternalServerError)
	default:
		w.WriteHeader(http.StatusInternalServerError)
	}
}

func createGetVehiclesRequest() application.GetVehiclesRequest {
	return application.NewGetVehiclesRequest(
		domain.VehicleTypeScooter,
		domain.VehicleStatusAvailable,
		52.419687,
		13.157065,
		52.597160,
		13.601000,
	)
}
