package domain

type VehicleType string
type VehicleStatus string

const (
	VehicleTypeScooter VehicleType = "scooter"

	VehicleStatusAvailable VehicleStatus = "available"
	VehicleStatusOccupied  VehicleStatus = "occupied"
)
