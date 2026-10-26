#include <iostream>
#include <vector>
#include <string>
#include <iomanip>
#include <cstdlib>
using namespace std;

string obtenerNombrePostre (int opcion) {
	
	switch (opcion) {
	case 1: return "Tiramisu";
	case 2: return "Flan con dulce de leche";
	case 3: return "Ensalada de frutas";
	case 4: return "Bocha de helado";
	case 5: return "Budin de pan";
	case 6: return "Panqueques con dulce de leche";
	case 7: return "Brownie con helado";
	default: return "Sin postre";
	}
	
}

struct Pizzeria{ 
	int mesa;
	int menu;
	int postre;
};

int main(int argc, char *argv[]) {
	
	cout << setw(20) << "\033[33m" << "BLOCK PIZZA" << "\033[0m" << endl;
				
	vector<Pizzeria> listaDePedidos; // Vector que guardará muchos pedidos
	
	char continuar = 's', pedirPostre; // Un char para pedirle al usuario si quiere continuar o no en el while y uno si quiere postre o no
	
	while (continuar == 's' || continuar == 'S') { //Un bucle para cargar muchos pedidos
		Pizzeria nuevoPedido; //Pedido temporal
		nuevoPedido.postre = 0; // Inicializamos el pedido sin postre automaticamente
		
		cout<<"Todas los pedidos vienen con gaseosa/postre a elección. "<<endl;
		
		cout << "Ingrese el número de la mesa: ";
		cin >> nuevoPedido.mesa;
		
		cout << "Ingresa la opción de menu: ";
		cin >> nuevoPedido.menu;

		
		switch (nuevoPedido.menu){
			
		case 1: cout << "Pizza a la Napolitana" << endl; break;
		case 2: cout << "Pizza con ananá" << endl; break;
		case 3: cout << "Pizza con jamón y muzzarella" << endl; break;
		case 4: cout << "Pizza con anchoas" << endl; break;
		case 5: cout << "Pizza con especial con jamón y tomate" << endl; break;
		case 6: cout << "Pizza con burrata y jamón crudo" << endl; break;
		case 7: cout << "Pizza con pollo al verdeo" << endl; break;
		case 8: cout << "Pizza con masa madre" << endl; break;
		case 9: cout << "Foccacia rellena" << endl; break;
		case 10: cout << "Pizza al molde" << endl; break;
			
	 }
		
		cout << "Desea agregar postre? (s/n): ";
		cin >> pedirPostre;
		
		if (pedirPostre == 's' || pedirPostre == 'S') {
			
			cout << "Ingrese la opción de postre: ";
			cin >> nuevoPedido.postre;
			
			cout << obtenerNombrePostre(nuevoPedido.postre) << endl;
			
		} else {
			nuevoPedido.postre = 0;
		}
		
		
string nombrePostreTxt = obtenerNombrePostre(nuevoPedido.postre);

string comando = "curl -s -X POST "
                 "--data-urlencode \"mesa=" + to_string(nuevoPedido.mesa) + "\" "
                 "--data-urlencode \"menu=" + to_string(nuevoPedido.menu) + "\" "
                 "--data-urlencode \"postre=" + nombrePostreTxt + "\" "
                 "http://localhost/prueba/pedido.php";

system(comando.c_str());

        cout << "\n>> ¡Pedido procesado y enviado a la base de datos!\n" << endl;

		cout << "\nDesea realizar otro pedido? (s/n): \n";
		cin >> continuar;
		
	}
	
	
	cout << "\n--- RESUMEN DE PEDIDOS DEL DIA ---\n";
	for (int i = 0; i < listaDePedidos.size(); i++) {
		cout << "Pedido " << i + 1 << " -> Mesa: " << listaDePedidos[i].mesa 
			<< ", Menu: " << listaDePedidos[i].menu
			<< ", Postre: " << obtenerNombrePostre(listaDePedidos[i].postre) << endl;
	}
	
	return 0;
}

