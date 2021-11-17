package infrastructure

import (
	"bytes"
	"encoding/json"
	"errors"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"reflect"
	"time"

	"github.com/v-smirnov/scootin/app/application"
)

const (
	updateVehicleLocationUrlTemplate = "%s/api/vehicle/%s/location"
	updateVehicleStatusUrlTemplate   = "%s/api/vehicle/%s/status/%s"
	getVehiclesUrlTemplate           = "%s/api/vehicles/%s/status/%s"

	apiKeyHeader = "API-KEY"
)

type ApiClient interface {
	UpdateVehicleLocation(request application.UpdateVehicleLocationRequest) error
	UpdateVehicleStatus(request application.UpdateVehicleStatusRequest) error
	GetVehicles(request application.GetVehiclesRequest) (*application.GetVehiclesResponse, error)
}

type apiClient struct {
	baseUrl string
	apiKey  string
}

func NewApiClient(baseUrl string, apikey string) ApiClient {
	return &apiClient{
		baseUrl: baseUrl,
		apiKey:  apikey,
	}
}

func (c *apiClient) UpdateVehicleLocation(requestDto application.UpdateVehicleLocationRequest) error {
	url := fmt.Sprintf(updateVehicleLocationUrlTemplate, c.baseUrl, requestDto.VehicleIdentifier)

	resp, err := c.performRequest(url, http.MethodPut, requestDto)
	if err != nil {
		return err
	}
	defer resp.Body.Close()

	return resolveResponseError(resp)
}

func (c *apiClient) UpdateVehicleStatus(requestDto application.UpdateVehicleStatusRequest) error {
	url := fmt.Sprintf(updateVehicleStatusUrlTemplate, c.baseUrl, requestDto.VehicleIdentifier, requestDto.VehicleStatus)

	resp, err := c.performRequest(url, http.MethodPut, requestDto)
	if err != nil {
		return err
	}
	defer resp.Body.Close()

	return resolveResponseError(resp)
}

func (c *apiClient) GetVehicles(requestDto application.GetVehiclesRequest) (*application.GetVehiclesResponse, error) {
	url := fmt.Sprintf(getVehiclesUrlTemplate, c.baseUrl, requestDto.VehicleType, requestDto.VehicleStatus)

	resp, err := c.performRequest(url, http.MethodGet, requestDto)
	if err != nil {
		return nil, err
	}
	defer resp.Body.Close()

	if err = resolveResponseError(resp); err != nil {
		return nil, err
	}

	respBody, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return nil, err
	}

	getVehicleResponse := &application.GetVehiclesResponse{}
	if err = json.Unmarshal(respBody, &getVehicleResponse); err != nil {
		return nil, err
	}
	log.Println(fmt.Sprintf("received %d vehicles", len(getVehicleResponse.Vehicles)))

	return getVehicleResponse, nil
}

func (c *apiClient) performRequest(url string, method string, requestDto interface{}) (*http.Response, error) {
	body, _ := json.Marshal(requestDto)
	req, _ := http.NewRequest(method, url, bytes.NewBuffer(body))
	req.Header.Add(apiKeyHeader, c.apiKey)

	log.Println(
		fmt.Sprintf(
			"sending [%s] %s; url: %s; client: %s; body: %s",
			method, reflect.TypeOf(requestDto), url, c.apiKey, string(body),
		),
	)
	resp, err := getHttpClient().Do(req)
	if err != nil {
		return nil, err
	}

	return resp, nil
}

func resolveResponseError(resp *http.Response) error {
	if resp.StatusCode >= http.StatusInternalServerError {
		respBody, _ := ioutil.ReadAll(resp.Body)
		return errors.New(fmt.Sprintf("server error: %s", string(respBody)))
	}

	if resp.StatusCode >= http.StatusBadRequest {
		respBody, _ := ioutil.ReadAll(resp.Body)
		return errors.New(fmt.Sprintf("invalid request: %s", string(respBody)))
	}

	return nil
}

func getHttpClient() *http.Client {
	return &http.Client{
		Timeout:   time.Second * 10,
		Transport: http.DefaultTransport,
	}
}
