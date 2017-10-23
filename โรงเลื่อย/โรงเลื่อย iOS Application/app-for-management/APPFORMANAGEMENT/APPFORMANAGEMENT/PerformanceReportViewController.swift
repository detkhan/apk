//
//  PerformanceReportViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 2/1/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import Charts
import SwiftyJSON
import Alamofire
import KRProgressHUD
class PerformanceReportViewController: UIViewController {
    
    @IBOutlet weak var productObjbarChartView: CombinedChartView!
    @IBOutlet weak var productObjSecondbarChartView: CombinedChartView!
    @IBOutlet weak var productObjThirdbarChartView: CombinedChartView!
    
    @IBOutlet weak var dateLabel:UILabel!
    @IBOutlet weak var dropdownImage:UIImageView!
    @IBOutlet weak var graphView:UIView!
    @IBOutlet weak var scrollview:UIScrollView!
    @IBOutlet weak var datePickerView:UIView!
    @IBOutlet weak var datePicker:MonthYearPickerView!
    
    @IBOutlet weak var tableviewVolumeProd:UITableView!
    @IBOutlet weak var tableviewABGoals:UITableView!
    @IBOutlet weak var tableviewABCGoals:UITableView!
    
    var volumeProductObjOnTable = [Volumeproduct]()
    var volumeProductABGoalObjOnTable = [ABGoals]()
    var volumeProductABCGoalObjOnTable = [ABCGoals]()
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewWillAppear(_ animated: Bool) {
        setNavBar()
        setStringAndDisplay()
        setScrolling()
        getCurrentDate()
        getIntensivePerformance()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        clearalldata()
    }
    
    func setNavBar(){
        let newToDo = ["TITLE_TEXT":"PERFORMANCE REPORT",
                       "LEFT_BUTTON":"onClickedToBack",
                       "IMAGE_NAME":"back_icon"]
        TabNavigationView.addNavigationView(dict: newToDo as [String : String], viewController: self ,buttonHidden: false)
    }
    
    func setStringAndDisplay(){
        dropdownImage.image = UIImage(named: "dropdown")
    }
    
    func getIntensivePerformance() {
        let date = Date()
        let monthformatter = DateFormatter()
        let yearformatter = DateFormatter()
        monthformatter.dateFormat = "MM"
        yearformatter.dateFormat = "YYYY"
        intensivePerformance(month: monthformatter.string(from: date), years: yearformatter.string(from: date))
    }
    
    func intensivePerformance(month:String,years:String){
        showHUD()
        APKController.shareInstance.performancedelegate = self
        let parameter = ["email" : SetttingController.shareInstance.getEmail(), "dateMonth" : month , "years" : years]
        APKController.shareInstance.IntensivePerformance(parameter: parameter, Key: ServiceKey.INTENSIVE_PERFORMANCE.rawValue)
    }
    
    func getCurrentDate(){
        let date = Date()
        let formatter = DateFormatter()
        formatter.dateFormat = "MM/YYYY"
        let currentDate = formatter.string(from: date)
        dateLabel.text = currentDate
    }
    
    func setScrolling(){
        scrollview.showsVerticalScrollIndicator = false
        scrollview.showsHorizontalScrollIndicator = false
        scrollview.addSubview(graphView)
        scrollview.contentSize = CGSize.init(width: self.graphView.frame.size.width, height: self.graphView.frame.size.height)
    }
    func clearalldata(){
        volumeProductObjOnTable.removeAll()
        volumeProductABGoalObjOnTable.removeAll()
        volumeProductABCGoalObjOnTable.removeAll()
    }
}

extension PerformanceReportViewController {
    func showHUD(){
        KRProgressHUD.show(progressHUDStyle: .white, message: "Loading...")
    }
    func hideHUD(){
        DispatchQueue.main.async {
            KRProgressHUD.dismiss()
        }
    }
}

//MARK : - Actions (Back actions)
extension PerformanceReportViewController{
    @IBAction func onClickedToBack(){
        _ = navigationController?.popViewController(animated: true)
    }
}
//MARK : - Actions (Click To Show Calendar)
extension PerformanceReportViewController {
    @IBAction func onClikcedToShowCalendar(){
        datePickerView.isHidden = false
    }
    @IBAction func onClikcedToCloseCalendar(){
        datePickerView.isHidden = true
    }
    @IBAction func onCLikcedToSelectDate(){
        dateLabel.text = String(format:"%02d/%d",datePicker.month,datePicker.year)
        intensivePerformance(month: String(format: "%02d",datePicker.month), years: String(format: "%d",datePicker.year))
        datePickerView.isHidden = true
    }
}

//MARK : - Create Charts Volume_Product/Goals
extension PerformanceReportViewController{
    func firstChart(shortName:Array<String>,volumeproduct:Array<Double>,goals:Array<Double>){
        //barChartView.delegate = self
        productObjbarChartView.noDataText = "You need to provide data for the chart."
        productObjbarChartView.chartDescription?.text = "" //set description for barChartView
        //disable zoom
        productObjbarChartView.doubleTapToZoomEnabled = false
        productObjbarChartView.pinchZoomEnabled = false

        setFirstChart(shortname: shortName, volume: volumeproduct, goals: goals)
    }
    
    func setFirstChart(shortname:Array<String>,volume:Array<Double>,goals:Array<Double>){
        productObjbarChartView.noDataText = "You need to provide data for the chart."
        var dataEntrieszor : [ChartDataEntry] = []
        var dataEntries : [BarChartDataEntry] = []
        var limitline = ChartLimitLine()
        productObjbarChartView.rightAxis.removeAllLimitLines()
        if volume.count > 0 {
            for i in 0..<shortname.count {
                dataEntrieszor.append(ChartDataEntry.init(x: Double(i), y: goals[i]))
                dataEntries.append(BarChartDataEntry.init(x: Double(i), y: volume[i]))
            }
            
            if shortname.count <= 1 {
                if goals.count > 0 {
                    for i in 0..<goals.count {
                        limitline = ChartLimitLine.init(limit: goals[i], label: "")
                        limitline.lineColor = UIColor.init(red: 95/255, green: 200/255, blue: 231/255, alpha: 1)
                        productObjbarChartView.rightAxis.addLimitLine(limitline)
                    }
                }
            }
            
            //line chart
            let lineChartSet = LineChartDataSet.init(values: dataEntrieszor, label: "Goals")
            lineChartSet.colors = [UIColor(red: 95/255, green: 200/255, blue: 231/255, alpha: 1)]
            lineChartSet.lineWidth = 2.0
            lineChartSet.drawCirclesEnabled = true
            let lineChartData = LineChartData.init(dataSets: [lineChartSet])
            //bar chart
            let barChartSet = BarChartDataSet.init(values: dataEntries, label: "Volume/Goals")
            barChartSet.colors = [UIColor(red: 238/255, green: 114/255, blue: 71/255, alpha: 1)]

            let barChartData = BarChartData.init(dataSets: [barChartSet])
            let data : CombinedChartData = CombinedChartData.init(dataSets: [lineChartSet,barChartSet])
            data.lineData = lineChartData
            data.barData = barChartData
            productObjbarChartView.data = data
        
            //format combinechart
            productObjbarChartView.xAxis.axisMinimum = 0
            productObjbarChartView.xAxis.axisMinimum = -0.5
            if shortname.count > 0 {
                productObjbarChartView.xAxis.axisMaximum = Double(shortname.count)
                productObjbarChartView.xAxis.axisMaximum = Double(shortname.count) - 0.5
            }
            productObjbarChartView.notifyDataSetChanged()
            productObjbarChartView.xAxis.valueFormatter = IndexAxisValueFormatter(values:shortname)
            productObjbarChartView.xAxis.granularity = 1
            productObjbarChartView.xAxis.drawGridLinesEnabled = true
            productObjbarChartView.xAxis.labelPosition = .bottom
            productObjbarChartView.rightAxis.drawLabelsEnabled = false
            productObjbarChartView.rightAxis.drawGridLinesEnabled = false
            //productObjbarChartView.xAxis.centerAxisLabelsEnabled = true
            productObjbarChartView.data?.highlightEnabled = false
            productObjbarChartView.rightAxis.enabled = false
        
            //animated
            productObjbarChartView.animate(xAxisDuration: 1.5, easingOption: .linear)
            //productObjbarChartView.animate(yAxisDuration: 1.5, easingOption: .easeInBounce)
        }else{
            dataEntries.removeAll()
            dataEntrieszor.removeAll()
            productObjbarChartView.data?.notifyDataChanged()
            productObjbarChartView.notifyDataSetChanged()
            productObjbarChartView.data = nil
        }
    }
}
//MARK : - Create Charts Yeild AB/Goals
extension PerformanceReportViewController {
    
    func secondChart(shortName:Array<String>,volumeproduct:Array<Double>,goals:Array<Double>){

        productObjSecondbarChartView.noDataText = "You need to provide data for the chart."
        productObjSecondbarChartView.chartDescription?.text = "" //set description for barChartView
        //disable zoom
        productObjSecondbarChartView.doubleTapToZoomEnabled = false
        productObjSecondbarChartView.pinchZoomEnabled = false
        
        setSecondChart(shortname: shortName, volume: volumeproduct, goals: goals)
    }

    func setSecondChart(shortname:Array<String>,volume:Array<Double>,goals:Array<Double>){
        productObjSecondbarChartView.noDataText = "You need to provide data for the chart."
        var dataEntrieszor : [ChartDataEntry] = []
        var dataEntries : [BarChartDataEntry] = []
        var limitline = ChartLimitLine()
        productObjSecondbarChartView.rightAxis.removeAllLimitLines()
        if volume.count > 0 {
            for i in 0..<shortname.count {
                dataEntrieszor.append(ChartDataEntry.init(x: Double(i), y: goals[i]))
                dataEntries.append(BarChartDataEntry.init(x: Double(i), y: volume[i]))
            }
            
            if shortname.count <= 1 {
                if goals.count > 0 {
                    for i in 0..<goals.count {
                        limitline = ChartLimitLine.init(limit: goals[i], label: "")
                        limitline.lineColor = UIColor.init(red: 95/255, green: 200/255, blue: 231/255, alpha: 1)
                        productObjSecondbarChartView.rightAxis.addLimitLine(limitline)
                    }
                }
            }
            
            //line chart
            let lineChartSet = LineChartDataSet.init(values: dataEntrieszor, label: "Goals")
            lineChartSet.colors = [UIColor(red: 95/255, green: 200/255, blue: 231/255, alpha: 1)]
            lineChartSet.lineWidth = 3.0
            lineChartSet.drawCirclesEnabled = true
            let lineChartData = LineChartData.init(dataSets: [lineChartSet])
            //bar chart
            let barChartSet : BarChartDataSet = BarChartDataSet.init(values: dataEntries, label: "YieldAB/Goals")
            barChartSet.colors = [UIColor(red: 238/255, green: 114/255, blue: 71/255, alpha: 1)]
            let barChartData = BarChartData.init(dataSets: [barChartSet])
        
            let data : CombinedChartData = CombinedChartData.init(dataSets: [lineChartSet,barChartSet])
            data.lineData = lineChartData
            data.barData = barChartData
            productObjSecondbarChartView.data = data
            
            //format
            productObjSecondbarChartView.xAxis.axisMinimum = Double(0.0)
            productObjSecondbarChartView.xAxis.axisMinimum = -0.5
            if shortname.count > 0 {
                productObjSecondbarChartView.xAxis.axisMaximum = Double(shortname.count)
                productObjSecondbarChartView.xAxis.axisMaximum = Double(shortname.count) - 0.5
            }
            productObjSecondbarChartView.notifyDataSetChanged()
            productObjSecondbarChartView.xAxis.valueFormatter = IndexAxisValueFormatter(values:shortname)
            productObjSecondbarChartView.xAxis.granularity = 1
            productObjSecondbarChartView.xAxis.drawGridLinesEnabled = true
            productObjSecondbarChartView.xAxis.labelPosition = .bottom
            productObjSecondbarChartView.rightAxis.drawLabelsEnabled = false
            productObjSecondbarChartView.rightAxis.drawGridLinesEnabled = false
            //productObjSecondbarChartView.xAxis.centerAxisLabelsEnabled = true
            productObjSecondbarChartView.rightAxis.enabled = false
            productObjSecondbarChartView.data?.highlightEnabled = false
            //animated
            productObjSecondbarChartView.animate(xAxisDuration: 1.5, easingOption: .linear)
            //productObjSecondbarChartView.animate(yAxisDuration: 1.5, easingOption: .easeInBounce)
        }else{
            dataEntries.removeAll()
            dataEntrieszor.removeAll()
            productObjSecondbarChartView.data?.notifyDataChanged()
            productObjSecondbarChartView.notifyDataSetChanged()
            productObjSecondbarChartView.data = nil
        }
    }
}
//MARK : - Create Charts Yeild AB+C/Goals
extension PerformanceReportViewController {
    
    func thirdChart(shortName:Array<String>,volumeproduct:Array<Double>,goals:Array<Double>){
        //barChartView.delegate = self
        productObjThirdbarChartView.noDataText = "You need to provide data for the chart."
        productObjThirdbarChartView.chartDescription?.text = "" //set description for barChartView
        //disable zoom
        productObjThirdbarChartView.doubleTapToZoomEnabled = false
        productObjThirdbarChartView.pinchZoomEnabled = false
        
        setThirdChart(shortname: shortName, volume: volumeproduct, goals: goals)
        
    }
    
    func setThirdChart(shortname:Array<String>,volume:Array<Double>,goals:Array<Double>){
        productObjThirdbarChartView.noDataText = "You need to provide data for the chart."
        var dataEntrieszor : [ChartDataEntry] = []
        var dataEntries : [BarChartDataEntry] = []
        var limitline = ChartLimitLine()
        productObjThirdbarChartView.rightAxis.removeAllLimitLines()
        if volume.count > 0 {
            for i in 0..<shortname.count {
                dataEntrieszor.append(ChartDataEntry.init(x: Double(i), y: goals[i]))
                dataEntries.append(BarChartDataEntry.init(x: Double(i), y: volume[i]))
            }
            
            if shortname.count <= 1 {
                if goals.count > 0 {
                    for i in 0..<goals.count {
                        limitline = ChartLimitLine.init(limit: goals[i], label: "")
                        limitline.lineColor = UIColor.init(red: 95/255, green: 200/255, blue: 231/255, alpha: 1)
                        productObjThirdbarChartView.rightAxis.addLimitLine(limitline)
                    }
                }
            }
            
            //line chart
            let lineChartSet = LineChartDataSet.init(values: dataEntrieszor, label: "Goals")
            lineChartSet.colors = [UIColor(red: 95/255, green: 200/255, blue: 231/255, alpha: 1)]
            lineChartSet.lineWidth = 3.0
            lineChartSet.drawCirclesEnabled = true
            let lineChartData = LineChartData.init(dataSets: [lineChartSet])
            //bar chart
            let barChartSet : BarChartDataSet = BarChartDataSet.init(values: dataEntries, label: "YieldAB+C/Goals")
            barChartSet.colors = [UIColor(red: 238/255, green: 114/255, blue: 71/255, alpha: 1)]
            let barChartData = BarChartData.init(dataSets: [barChartSet])

            let data : CombinedChartData = CombinedChartData.init(dataSets: [lineChartSet,barChartSet])
            data.lineData = lineChartData
            data.barData = barChartData
            productObjThirdbarChartView.data = data
        
            //format
            productObjThirdbarChartView.xAxis.axisMinimum = Double(0.0)
            productObjThirdbarChartView.xAxis.axisMinimum = -0.5
            if shortname.count > 0 {
                productObjThirdbarChartView.xAxis.axisMaximum = Double(shortname.count)
                productObjThirdbarChartView.xAxis.axisMaximum = Double(shortname.count) - 0.5
            }
            productObjThirdbarChartView.notifyDataSetChanged()
            productObjThirdbarChartView.xAxis.valueFormatter = IndexAxisValueFormatter(values:shortname)
            productObjThirdbarChartView.xAxis.granularity = 1
            productObjThirdbarChartView.xAxis.drawGridLinesEnabled = true
            productObjThirdbarChartView.xAxis.labelPosition = .bottom
            productObjThirdbarChartView.rightAxis.drawLabelsEnabled = false
            productObjThirdbarChartView.rightAxis.drawGridLinesEnabled = false
            //productObjThirdbarChartView.xAxis.centerAxisLabelsEnabled = true
            productObjThirdbarChartView.rightAxis.enabled = false
            productObjThirdbarChartView.data?.highlightEnabled = false
            //animated
            productObjThirdbarChartView.animate(xAxisDuration: 1.5, easingOption: .linear)
            //productObjThirdbarChartView.animate(yAxisDuration: 1.5, easingOption: .easeInBounce)
        }else{
            dataEntries.removeAll()
            dataEntrieszor.removeAll()
            productObjThirdbarChartView.data?.notifyDataChanged()
            productObjThirdbarChartView.notifyDataSetChanged()
            productObjThirdbarChartView.data = nil
        }
    }
}
//MARK : - Delegate TableView
extension PerformanceReportViewController : UITableViewDelegate , UITableViewDataSource {
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if tableView == self.tableviewVolumeProd {
            return volumeProductObjOnTable.count
        }else if tableView == self.tableviewABGoals {
            return volumeProductABGoalObjOnTable.count
        }else if tableView == self.tableviewABCGoals{
            return volumeProductABCGoalObjOnTable.count
        }
        return 0
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        if tableView == self.tableviewVolumeProd {
            let cell:volumeProdCell = self.tableviewVolumeProd.dequeueReusableCell(withIdentifier: "volumeProdCell", for: indexPath) as! volumeProdCell
            let volume = volumeProductObjOnTable[indexPath.row]
            cell.shortName.text = String(format:"%@ Station",volume.shortname)
            cell.datalbl.text = String(format:"%@ ft3/Ton",volume.volumeproduct)
            return cell
        }else if tableView == self.tableviewABGoals {
            let cell:volumeABGoalsCell = self.tableviewABGoals.dequeueReusableCell(withIdentifier: "volumeABGoalsCell", for: indexPath) as! volumeABGoalsCell
            let volume = volumeProductABGoalObjOnTable[indexPath.row]
            cell.shortName.text = String(format:"%@ Station",volume.shortname)
            cell.datalbl.text = String(format:"%@ ft3/Ton",volume.volumeproduct)
            return cell
        }else{
            let cell:volumeABCGoalsCell = self.tableviewABCGoals.dequeueReusableCell(withIdentifier: "volumeABCGoalsCell", for: indexPath) as! volumeABCGoalsCell
            let volume = volumeProductABCGoalObjOnTable[indexPath.row]
            cell.shortName.text = String(format:"%@ Station",volume.shortname)
            cell.datalbl.text = String(format:"%@ ft3/Ton",volume.volumeproduct)
            return cell
        }
    }
}

struct Volumeproduct {
    let fullname : String
    let shortname : String
    let volumeproduct : String
    let goals : String
}

struct ABGoals {
    let fullname : String
    let shortname : String
    let volumeproduct : String
    let goals : String
}

struct ABCGoals {
    let fullname : String
    let shortname : String
    let volumeproduct : String
    let goals : String
}

extension PerformanceReportViewController : PerformanceDelegate {
    
    func intensivePerformanceRes(response: DataResponse<Any>) {
        hideHUD()
        clearalldata()
        var volumeProductObj = [Volumeproduct]()
        var volumeProductABGoalObj = [ABGoals]()
        var volumeProductABCGoalObj = [ABCGoals]()
        
        var shortname1:Array<String> = []
        var volumeproduct1:Array<Double> = []
        var goals1:Array<Double> = []
        
        var shortname2:Array<String> = []
        var volumeproduct2:Array<Double> = []
        var goals2:Array<Double> = []
        
        var shortname3:Array<String> = []
        var volumeproduct3:Array<Double> = []
        var goals3:Array<Double> = []
        
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let volumeProductArray = json[1][0]["Volume_Product/Goals"].arrayValue
                    for volumeprod in volumeProductArray {
                        let fullname = volumeprod["fullname"].stringValue
                        let name = volumeprod["name"].stringValue
                        let goals = volumeprod["goals"].stringValue
                        let volumeproduct = volumeprod["volume_product"].stringValue
                        let parameter = Volumeproduct(fullname:fullname,shortname:name,volumeproduct:volumeproduct,goals:goals)
                        volumeProductObj.append(parameter)
                        volumeProductObjOnTable.append(parameter)
                    }
                }
                for volumeProd in volumeProductObj {
                    shortname1.append(volumeProd.shortname)
                    volumeproduct1.append(Double(volumeProd.volumeproduct)!)
                    goals1.append(Double(volumeProd.goals)!)
                    
                }
                ConcurrentQueue.sync {
                    let volumeProductABGoalsArray = json[1][0]["AB/Goals"].arrayValue
                    for volumeprod in volumeProductABGoalsArray {
                        let fullname = volumeprod["fullname"].stringValue
                        let name = volumeprod["name"].stringValue
                        let goals = volumeprod["goals"].stringValue
                        let volumeproduct = volumeprod["volume_product"].stringValue
                        let parameter = ABGoals(fullname:fullname,shortname:name,volumeproduct:volumeproduct,goals:goals)
                        volumeProductABGoalObj.append(parameter)
                        volumeProductABGoalObjOnTable.append(parameter)
                    }
                }
                for volumeProd in volumeProductABGoalObj {
                    shortname2.append(volumeProd.shortname)
                    volumeproduct2.append(Double(volumeProd.volumeproduct)!)
                    goals2.append(Double(volumeProd.goals)!)
                    
                }
                ConcurrentQueue.sync {
                    let volumeProductABCGoalsArray = json[1][0]["AB+C/Goals"].arrayValue
                    for volumeprod in volumeProductABCGoalsArray {
                        let fullname = volumeprod["fullname"].stringValue
                        let name = volumeprod["name"].stringValue
                        let goals = volumeprod["goals"].stringValue
                        let volumeproduct = volumeprod["volume_product"].stringValue
                        let parameter = ABCGoals(fullname:fullname,shortname:name,volumeproduct:volumeproduct,goals:goals)
                        volumeProductABCGoalObj.append(parameter)
                        volumeProductABCGoalObjOnTable.append(parameter)
                    }
                }
                for volumeProd in volumeProductABCGoalObj{
                    shortname3.append(volumeProd.shortname)
                    volumeproduct3.append(Double(volumeProd.volumeproduct)!)
                    goals3.append(Double(volumeProd.goals)!)
                }
                DispatchQueue.main.async{
                    self.firstChart(shortName: shortname1, volumeproduct: volumeproduct1, goals: goals1)
                    self.secondChart(shortName: shortname2, volumeproduct: volumeproduct2, goals: goals2)
                    self.thirdChart(shortName: shortname3, volumeproduct: volumeproduct3, goals: goals3)
                    self.tableviewVolumeProd.reloadData()
                    self.tableviewABGoals.reloadData()
                    self.tableviewABCGoals.reloadData()
                }
            }else{
                clearalldata()
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
}
