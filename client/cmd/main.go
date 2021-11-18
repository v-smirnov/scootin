package main

import (
	"fmt"
	"log"
	"math/rand"
	"os"
	"sync"
	"time"

	"github.com/v-smirnov/scootin/app/application"
	"github.com/v-smirnov/scootin/app/domain"
	"github.com/v-smirnov/scootin/app/infrastructure"
)

const (
	//defaultBaseApiUrl is used if BASE_URL env variable is not set up
	defaultBaseApiUrl = "http://localhost:5555"

	//this set of constants responsible for area, where app try to search vehicles
	startLatitude = 52.419687
	startLongitude = 13.157065
	endLatitude = 52.597160
	endLongitude = 13.601000

	requestDateTimeFormat = "2006-01-02 15:04:05"

	//totalRides - amount of total fake rides
	totalRides = 5

	//maxActiveClients - amount of fake clients, working in parallel
	maxActiveClients = 3
)

var (
	// set of keys to emulate different clients
	apiKeys = [7]string{"api_key_1", "api_key_2", "api_key_3", "api_key_4", "api_key_5", "api_key_6", "api_key_7"}
)

func main() {
	//use WaitGroup to wait all working clients to finish their requests
	wg := &sync.WaitGroup{}

	limitCh := make(chan struct{}, maxActiveClients)
	for i := 0; i < totalRides; i++ {
		wg.Add(1)
		go runFakeClient(wg, limitCh)
	}

	wg.Wait()
}

func runFakeClient(wg *sync.WaitGroup, limitCh chan struct{}) {
	//use this channel to limit amount of clients working in parallel
	limitCh <- struct{}{}

	defer wg.Done()
	defer func() {
		<-limitCh
	}()

	apiClient := infrastructure.NewApiClient(resolveBaseUrl(), getRandomApiKey())

	availableVehicles, err := getAvailableVehicles(apiClient)

	if err != nil {
		log.Println("could not fetch vehicle list", err)
		return
	}
	if len(availableVehicles.Vehicles) == 0 {
		log.Println("all vehicles are occupied", err)
		return
	}

	vehicle := getRandomVehicle(availableVehicles.Vehicles)

	if err = markVehicleAsOccupied(apiClient, vehicle); err != nil {
		log.Println(err)
		return
	}

	if err = emulateRide(apiClient, vehicle); err != nil {
		log.Println(err)
		return
	}

	if err = markVehicleAsAvailable(apiClient, vehicle); err != nil {
		log.Println(err)
		return
	}
}

func resolveBaseUrl() string {
	baseUrl := os.Getenv("BASE_URL")
	if baseUrl == "" {
		baseUrl = defaultBaseApiUrl
	}

	return baseUrl
}

func getAvailableVehicles(apiClient infrastructure.ApiClient) (*application.GetVehiclesResponse, error) {
	return apiClient.GetVehicles(
		application.NewGetVehiclesRequest(
			domain.VehicleTypeScooter,
			domain.VehicleStatusAvailable,
			startLatitude,
			startLongitude,
			endLatitude,
			endLongitude,
		),
	)
}

func markVehicleAsAvailable(apiClient infrastructure.ApiClient, vehicle application.Vehicle) error {
	return apiClient.UpdateVehicleStatus(
		application.NewUpdateVehicleStatusRequest(
			vehicle.VehicleIdentifier,
			domain.VehicleStatusAvailable,
			time.Now().Format(requestDateTimeFormat),
		),
	)
}

func emulateRide(apiClient infrastructure.ApiClient, vehicle application.Vehicle) error {
	// every 3 seconds we will send vehicle location
	ticker := time.NewTicker(time.Second * 3)

	rideStart := time.Now()
	for tickTime := range ticker.C {
		//if ride duration more than 10 seconds, stop the ride
		if time.Now().Sub(rideStart) > 10 * time.Second {
			ticker.Stop()
			break
		}

		err := apiClient.UpdateVehicleLocation(
			application.NewUpdateVehicleLocationRequest(
				vehicle.VehicleIdentifier,
				getRandomLatitudeFromArea(),
				getRandomLongitudeFromArea(),
				tickTime.Format(requestDateTimeFormat),
			),
		)

		if err != nil {
			log.Println(fmt.Sprintf("change vehicle %s location failed", vehicle.VehicleIdentifier))
		}
	}

	return nil
}

func markVehicleAsOccupied(apiClient infrastructure.ApiClient, vehicle application.Vehicle) error {
	return apiClient.UpdateVehicleStatus(
		application.NewUpdateVehicleStatusRequest(
			vehicle.VehicleIdentifier,
			domain.VehicleStatusOccupied,
			time.Now().Format(requestDateTimeFormat),
		),
	)
}

func getRandomVehicle(vehicles []application.Vehicle) application.Vehicle {
	rand.Seed(time.Now().UnixNano())

	return vehicles[rand.Intn(len(vehicles))]
}

func getRandomApiKey() string {
	rand.Seed(time.Now().UnixNano())

	return apiKeys[rand.Intn(len(apiKeys))]
}

func getRandomLatitudeFromArea() float32 {
	rand.Seed(time.Now().UnixNano())

	return startLatitude + ((endLatitude - startLatitude) / float32(rand.Intn(7) + 2))
}

func getRandomLongitudeFromArea() float32 {
	rand.Seed(time.Now().UnixNano())

	return startLongitude + ((endLongitude - startLongitude) / float32(rand.Intn(7) + 2))
}
