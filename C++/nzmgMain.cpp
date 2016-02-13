#include "NZMGTransform.h"

#include <iostream>
#include <string>
#include <vector>

int main () {

	//double east = 2765191.269581673;
	//double north = 6412583.869434468;
	//std::cout << std::to_string(argc - 1) << std::endl;
	std::string data;
	std::vector<double> vect;
	int count = 0;
	char nextChar;
	std::getline(std::cin, data);
	for (int i = 0; i < int(data.length()); i++) {
		nextChar = data.at(i);
		if (isspace(data[i]))
			count++;
	}

	//std::cout << count + 1 << std::endl;
	std::cout.precision(12);
	std::stringstream stream(data);
	double n;
    while(stream >> n){
        //stream >> n;
		//std::cout << n << std::endl;
		vect.push_back(n);
    }

	// for (auto i: vect)
	// 	std::cout << i << ' ';
	// std::cout << std::endl;
	
	//std::cout << count << std::endl;
	  
	for (int i = 0; i < count + 1; i+=2) {
		double east = -1;
		double north = -1;
		east = vect.at(i);
		north = vect.at(i+1);
		//std::cout << "Northing " + std::to_string(north) << std::endl;
		//std::cout << "Easting " + std::to_string(east) << std::endl;
		NZMGTransform x = NZMGTransform();
		double a[2] = {east, north};
		std::array<double, 2> b;

		b = x.nzmgToNZGD1949(a);
		if (i == count - 1) {
			std::cout << std::to_string(b[1]) + " " + std::to_string(b[0]);
		} else {
			std::cout << std::to_string(b[1]) + " " + std::to_string(b[0]) + " ";
		}
			
	}
	
	return 0;
}
