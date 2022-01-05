#!/usr/bin/env python
# coding: utf-8

# In[1]:


get_ipython().run_line_magic('reset', '-f')


# In[2]:


import redis
import json
from datetime import date
from datetime import timedelta

r = redis.Redis(host='localhost', port=6379, db=0)

dataCompany = json.loads(r.get('dataCompany')) 
cost_kWh_generation = float(dataCompany['priceSolarPanelGenerate'])

solarPanelGeneration = json.loads(r.get('solarPanelGeneration')) 
acum_kWh_generation = float(solarPanelGeneration['kWh_acumGeneration'])

prevDay = (date.today() - timedelta(days = 1)).strftime('%d/%m/%y')

#Generation history
data_setGenerationHistory = {"date":prevDay,"kWh":round(acum_kWh_generation,6),"benefit":round((acum_kWh_generation*cost_kWh_generation),3)}

json_dumpGenerationHistory = json.dumps(data_setGenerationHistory)
r.lpush('generationHistoryList',json_dumpGenerationHistory) 


# In[3]:


import redis
import json
from datetime import date
from datetime import timedelta

r = redis.Redis(host='localhost', port=6379, db=0)

dataCompany = json.loads(r.get('dataCompany')) 
cost_kWh_consumption = float(dataCompany['pricekWh'])

energyConsumption = json.loads(r.get('energyConsumption')) 
acum_kWh_consumption = float(energyConsumption['kWh_acum'])

given_date = (date.today() - timedelta(days = 1))
#given_date = date(2019, 6, 23)
first_day_of_month = given_date.replace(day=1).strftime('%d/%m/%y')
 
#print("\nFirst day of month: ", first_day_of_month, "\n")

#Consumption history

#Buscamos si en la tabla hash esta la key del primer dia este mes.

returnRedis = r.hget('consumptionHistoryHashList', first_day_of_month)
#print(returnRedis)

if returnRedis!=None:
    #Se ha encontrado la key, por lo tanto actualizamos los kWh acum del mes.
    tempJson = json.loads(returnRedis)
    tempJson['kWh']+=round(acum_kWh_consumption,6)
    tempJson['cost']=round((tempJson['kWh']*cost_kWh_consumption),3)
    
    json_dumpConsumptionHistoryUPD = json.dumps(tempJson)
    r.hset('consumptionHistoryHashList',first_day_of_month,json_dumpConsumptionHistoryUPD)
    
else:
    #No se ha encontrado la key, por lo tanto creamos una nueva.
    print("No hay nada!")
    
    # Guaranteed to get the next month. Force any_date to 28th and then add 4 days.
    next_month = (date.today() - timedelta(days = 1)).replace(day=28) + timedelta(days=4)
    #next_month = date(2019, 6, 23).replace(day=28) + timedelta(days=4)
    # Subtract all days that are over since the start of the month.
    last_day_of_month = (next_month - timedelta(days=next_month.day)).strftime('%d/%m/%y')
    
    data_setConsumptionHistory = {"endPeriod":last_day_of_month,"kWh":round(acum_kWh_consumption,6),"cost":round((acum_kWh_consumption*cost_kWh_consumption),3)}
    json_dumpConsumptionHistory = json.dumps(data_setConsumptionHistory)
    r.hset('consumptionHistoryHashList',first_day_of_month,json_dumpConsumptionHistory)


# In[4]:


pathDataCSV = 'data/REDD/low_freq2'
pathDataH5 = 'data/data.h5'


# In[5]:


import redis
import json
r = redis.Redis(host='localhost', port=6379, db=0)

appliances = json.loads(r.get('dataAppliances')) 

selectedAppliances = []

if appliances['Refrigerator']==True:
    selectedAppliances.append('fridge')
if appliances['Dryer']==True:
    selectedAppliances.append('washer dryer')
if appliances['Microwave']==True:
    selectedAppliances.append('microwave')
if appliances['Dishwasher']==True:
    selectedAppliances.append('dish washer')
if appliances['Oven']==True:
    selectedAppliances.append('electric oven')
if appliances['Electric Hob']==True:
    selectedAppliances.append('electric stove')
if appliances['Electric heater']==True:
    selectedAppliances.append('electric space heater')
    
print(selectedAppliances)


# In[6]:


import csv
import datetime

epoch = datetime.datetime.utcfromtimestamp(0)
dt = datetime.datetime.today().replace(hour=0, minute=0, second=0, microsecond=0) - datetime.timedelta(days=1)
print(dt)
epochTimeS=(dt - epoch).total_seconds()
epochTime=int(epochTimeS)
print(epochTime)



        
#CH3        
countChannelDataEpoch=0
epochTime=int(epochTimeS)
listCH3_modelWS=[]
with open('models/channel_3.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if(countChannelDataEpoch<86400):
            if 'electric oven' in selectedAppliances:
                valModelWs=row[0].split(' ')[1]
            else:
                valModelWs=0
            #print(epochTime)
            #print(valModelWs)
            listCH3_modelWS.append([epochTime,valModelWs])
            epochTime+=1
            countChannelDataEpoch+=1
        else:
            break
                
with open(pathDataCSV+'/house_1/channel_3.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in listCH3_modelWS:
        writer.writerow([i[0], i[1]])

        
#CH4        
countChannelDataEpoch=0
epochTime=int(epochTimeS)
listCH4_modelWS=[]
with open('models/channel_4.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if(countChannelDataEpoch<86400): 
            if 'fridge' in selectedAppliances:
                valModelWs=row[0].split(' ')[1]
            else:
                valModelWs=0
            #print(epochTime)
            #print(valModelWs)
            listCH4_modelWS.append([epochTime,valModelWs])
            epochTime+=1
            countChannelDataEpoch+=1
        else:
            break
                
with open(pathDataCSV+'/house_1/channel_4.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in listCH4_modelWS:
        writer.writerow([i[0], i[1]])

        
#CH5        
countChannelDataEpoch=0
epochTime=int(epochTimeS)
listCH5_modelWS=[]
with open('models/channel_5.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if(countChannelDataEpoch<86400):
            if 'dish washer' in selectedAppliances:
                valModelWs=row[0].split(' ')[1]
            else:
                valModelWs=0
            #print(epochTime)
            #print(valModelWs)
            listCH5_modelWS.append([epochTime,valModelWs])
            epochTime+=1
            countChannelDataEpoch+=1
        else:
            break
                
with open(pathDataCSV+'/house_1/channel_5.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in listCH5_modelWS:
        writer.writerow([i[0], i[1]])
        
        
#CH6        
countChannelDataEpoch=0
epochTime=int(epochTimeS)
listCH6_modelWS=[]
with open('models/channel_6.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if(countChannelDataEpoch<86400):
            if 'washer dryer' in selectedAppliances:
                valModelWs=row[0].split(' ')[1]
            else:
                valModelWs=0
            #print(epochTime)
            #print(valModelWs)
            listCH6_modelWS.append([epochTime,valModelWs])
            epochTime+=1
            countChannelDataEpoch+=1
        else:
            break
                
with open(pathDataCSV+'/house_1/channel_6.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in listCH6_modelWS:
        writer.writerow([i[0], i[1]])
        
        
#CH7        
countChannelDataEpoch=0
epochTime=int(epochTimeS)
listCH7_modelWS=[]
with open('models/channel_7.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if(countChannelDataEpoch<86400):
            if 'microwave' in selectedAppliances:
                valModelWs=row[0].split(' ')[1]
            else:
                valModelWs=0
            #print(epochTime)
            #print(valModelWs)
            listCH7_modelWS.append([epochTime,valModelWs])
            epochTime+=1
            countChannelDataEpoch+=1
        else:
            break
                
with open(pathDataCSV+'/house_1/channel_7.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in listCH7_modelWS:
        writer.writerow([i[0], i[1]])
    
    
#CH8        
countChannelDataEpoch=0
epochTime=int(epochTimeS)
listCH8_modelWS=[]
with open('models/channel_8.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if(countChannelDataEpoch<86400):
            if 'electric space heater' in selectedAppliances:
                valModelWs=row[0].split(' ')[1]
            else:
                valModelWs=0
            #print(epochTime)
            #print(valModelWs)
            listCH8_modelWS.append([epochTime,valModelWs])
            epochTime+=1
            countChannelDataEpoch+=1
        else:
            break
                
with open(pathDataCSV+'/house_1/channel_8.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in listCH8_modelWS:
        writer.writerow([i[0], i[1]])
        
        
#CH9        
countChannelDataEpoch=0
epochTime=int(epochTimeS)
listCH9_modelWS=[]
with open('models/channel_9.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if(countChannelDataEpoch<86400):
            if 'electric stove' in selectedAppliances:
                valModelWs=row[0].split(' ')[1]
            else:
                valModelWs=0
            #print(epochTime)
            #print(valModelWs)
            listCH9_modelWS.append([epochTime,valModelWs])
            epochTime+=1
            countChannelDataEpoch+=1
        else:
            break
                
with open(pathDataCSV+'/house_1/channel_9.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in listCH9_modelWS:
        writer.writerow([i[0], i[1]])


# In[7]:


#Solo para test
#CH1
epoch = datetime.datetime.utcfromtimestamp(0)
dt = datetime.datetime.today().replace(hour=0, minute=0, second=0, microsecond=0) - datetime.timedelta(days=1)
epochTimeS=(dt - epoch).total_seconds()

countChannelDataEpoch=0
epochTime=int(epochTimeS)
listCH1_modelWS=[]
with open('models/backupCh1OKoriginal.dat', 'r') as file:
    reader = csv.reader(file)
    for row in reader:
        if(countChannelDataEpoch<86400):
            valModelWs=row[0].split(' ')[1]
            #print(epochTime)
            #print(valModelWs)
            listCH1_modelWS.append([epochTime,valModelWs])
            epochTime+=1
            countChannelDataEpoch+=1
        else:
            break
                
with open(pathDataCSV+'/house_1/channel_1.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in listCH1_modelWS:
        writer.writerow([i[0], i[1]])


# In[8]:


import redis
import json
r = redis.Redis(host='localhost', port=6379, db=0)

appliances = json.loads(r.get('dataAppliances')) 

selectedAppliances = []

if appliances['Refrigerator']==True:
    selectedAppliances.append('fridge')
if appliances['Dryer']==True:
    selectedAppliances.append('washer dryer')
if appliances['Microwave']==True:
    selectedAppliances.append('microwave')
if appliances['Dishwasher']==True:
    selectedAppliances.append('dish washer')
if appliances['Oven']==True:
    selectedAppliances.append('electric oven')
if appliances['Electric Hob']==True:
    selectedAppliances.append('electric stove')
if appliances['Electric heater']==True:
    selectedAppliances.append('electric space heater')
    
print(selectedAppliances)


# In[9]:


import csv
rtList = r.lrange("rtList", 0, 86400);

rtList.reverse()


with open(pathDataCSV+'/house_1/channel_1.dat', 'w', newline='') as file:
    writer = csv.writer(file, delimiter=' ')
    for i in rtList:
        data = json.loads(i)
        writer.writerow([data['epochTime'], round(data['kW']*1000,2)])


# In[10]:


#from nilmtk.dataset_converters import convert_redd
from nilmtk.dataset_converters import convert_reddAlex
convert_reddAlex(pathDataCSV, pathDataH5)
#convert_redd('data/REDD/dades', 'data/dades.h5')


# In[11]:


from __future__ import print_function, division
import time

from matplotlib import rcParams
import matplotlib.pyplot as plt
import pandas as pd
import numpy as np
from six import iteritems

from nilmtk import DataSet, TimeFrame, MeterGroup, HDFDataStore
from nilmtk.legacy.disaggregate import CombinatorialOptimisation, FHMM
import nilmtk.utils

get_ipython().run_line_magic('matplotlib', 'inline')


# In[12]:


rcParams['figure.figsize'] = (13, 6)


# In[13]:


train = DataSet(pathDataH5)
test = DataSet(pathDataH5)

#print(test.buildings[1].elec.mains().load(physical_quantity = 'power', ac_type = 'apparent', sample_period=1))


# In[14]:


building = 1


# In[15]:


from datetime import date
from datetime import timedelta

day = (date.today() - timedelta(days = 1)).strftime('%Y-%m-%d')

print("Date to predict appliances consuption "+day)

#train => Model
#test => Data meter

train.set_window(end=day)
test.set_window(start=day)

#train.set_window(end="2021-10-20")
#test.set_window(start="2021-10-20")

#train.set_window(end="2011-05-01")
#test.set_window(start="2011-05-01")

train_elec = train.buildings[1].elec
test_elec = test.buildings[1].elec


# In[16]:


train_elec.plot()


# In[17]:


test_elec.mains().plot()


# In[18]:


def predict(clf, test_elec, sample_period, timezone):
    pred = {}
    gt= {}

    # "ac_type" varies according to the dataset used. 
    # Make sure to use the correct ac_type before using the default parameters in this code.    
    for i, chunk in enumerate(test_elec.mains().load(physical_quantity = 'power', ac_type = 'apparent', sample_period=sample_period)):
        chunk_drop_na = chunk.dropna()
        pred[i] = clf.disaggregate_chunk(chunk_drop_na)
        gt[i]={}

        for meter in test_elec.submeters().meters:
            # Only use the meters that we trained on (this saves time!)    
            gt[i][meter] = next(meter.load(physical_quantity = 'power', ac_type = 'active', sample_period=sample_period))
        gt[i] = pd.DataFrame({k:v.squeeze() for k,v in iteritems(gt[i]) if len(v)}, index=next(iter(gt[i].values())).index).dropna()
        
    # If everything can fit in memory
    gt_overall = pd.concat(gt)
    gt_overall.index = gt_overall.index.droplevel()
    pred_overall = pd.concat(pred)
    pred_overall.index = pred_overall.index.droplevel()

    # Having the same order of columns
    gt_overall = gt_overall[pred_overall.columns]
    
    #Intersection of index
    gt_index_utc = gt_overall.index.tz_convert("UTC")
    pred_index_utc = pred_overall.index.tz_convert("UTC")
    common_index_utc = gt_index_utc.intersection(pred_index_utc)
    
    common_index_local = common_index_utc.tz_convert(timezone)
    gt_overall = gt_overall.loc[common_index_local]
    pred_overall = pred_overall.loc[common_index_local]
    appliance_labels = [m for m in gt_overall.columns.values]
    gt_overall.columns = appliance_labels
    pred_overall.columns = appliance_labels
    return gt_overall, pred_overall


# In[19]:


#top_5_train_elec = train_elec.submeters().select_top_k(k=2)
top_5_train_elec = train_elec.submeters().select_using_appliances(type=selectedAppliances)

classifiers = {'CO':CombinatorialOptimisation(), 'FHMM':FHMM()}
predictions = {}
sample_period = 3 #Cambiar el sample_period a 1, cuando este en produccion
for clf_name, clf in classifiers.items():
    print("*"*20)
    print(clf_name)
    print("*" *20)
    start = time.time()
    # Note that we have given the sample period to downsample the data to 1 minute. 
    # If instead of top_5 we wanted to train on all appliance, we would write 
    # fhmm.train(train_elec, sample_period=60)
    clf.train(top_5_train_elec, sample_period=sample_period)
    end = time.time()
    print("Runtime =", end-start, "seconds.")
    gt, predictions[clf_name] = predict(clf, test_elec, sample_period, train.metadata['timezone'])


# In[20]:


appliance_labels = [m.label() for m in gt.columns.values]


# In[21]:


gt.columns = appliance_labels
predictions['CO'].columns = appliance_labels
predictions['FHMM'].columns = appliance_labels
#Create DataFrame
df = pd.DataFrame({predictions['CO'].head(25).to_string()})

#Convert the Columns to string
df[df.columns]=df[df.columns].astype(str)

#Create the list of column separator width 
SepWidth = [5,6,3,8]

#Temp dict
tempdf = {}
#Convert all the column to series
for i, eCol in enumerate(df):
    tempdf[i] = pd.Series(df[eCol]).str.pad(width=SepWidth[i])

#Final DataFrame
Fdf = pd.concat(tempdf, axis=1)
#print Fdf
#Export to csv
Fdf.to_csv("./testCO.txt", sep='\t', index=False, header=False, encoding='utf-8')



# In[22]:


predictions['CO'].head(17480)


# In[23]:


predictions['FHMM'].head(25)


# In[24]:


gt.head(25)


# In[25]:


print(predictions['FHMM'])


# In[26]:


import requests
import json
import datetime

yesterday = datetime.date.today() - datetime.timedelta(days=1)
print(yesterday)

TOKEN = "0964bd4b8ebfb83588fb5c57b7babfdb5d2356c05cc235f8e6df93a1de17a55e"
url = 'https://api.esios.ree.es/indicators/1001?start_date='+str(yesterday)+'T00:00&end_date='+str(yesterday)+'T23:59&time_trunc=hour'
headers = {'Accept':'application/json; application/vnd.esios-api-v2+json','Content-Type':'application/json','Host':'api.esios.ree.es','Authorization':'Token token=' + TOKEN}
response = requests.get(url, headers=headers)

costkWh_PVPC=[]


if response.status_code == 200:
    json_data = json.loads(response.text)

    for v in json_data['indicator']['values']:
        if v['geo_id']==8741:
            costkWh_PVPC.append(round(v['value']/1000, 5))
    
    print(costkWh_PVPC)


# In[27]:


import redis
import json
from datetime import date
from datetime import timedelta
from datetime import time

r = redis.Redis(host='localhost', port=6379, db=0)

def is_weekend(d):
    return d.weekday() > 4

def is_between(timeD, startT, endT):
    if endT == time(0,0):
        return startT <= timeD.time() <= time(23,59)
    else:
        return startT <= timeD.time() < endT
    

dataCompany2 = json.loads(r.get('dataCompany')) 
rateType = dataCompany2['rateType']
hourlyDiscrimination = dataCompany2['hourlyDiscrimination']

def getCostkWh(timestampDateRecv):
    if rateType=="regulated":
        print("dentro discriminacion horaria regulated")
        print(costkWh_PVPC[timestampDateRecv.hour])
        return float(costkWh_PVPC[timestampDateRecv.hour])
    elif rateType=="fixed":
        if hourlyDiscrimination=="true":
            if is_weekend(timestampDateRecv):
                print("dentro discriminacion horaria fixed weekend")
                return float(dataCompany2['pricekWhValle'])
            else:
                if (is_between(timestampDateRecv, time(10,0), time(14,0)) or is_between(timestampDateRecv, time(18,0), time(22,0))):
                    print("dentro discriminacion horaria fixed lab 10-14 18-22")
                    return float(dataCompany2['pricekWhPunta'])
                elif (is_between(timestampDateRecv, time(8,0), time(10,0)) or is_between(timestampDateRecv, time(14,0), time(18,0)) or is_between(timestampDateRecv, time(22,0), time(0,0)) ):
                    print("dentro discriminacion horaria fixed lab 8-10 14-18 22-24")
                    return float(dataCompany2['pricekWhLlano'])
                elif is_between(timestampDateRecv, time(0,0), time(8,0)):
                    print("dentro discriminacion horaria fixed lab 0-8")
                    return float(dataCompany2['pricekWhValle'])
        elif hourlyDiscrimination=="false":
            print("NO discriminacion horaria fixed")
            return float(dataCompany2['pricekWh'])


# In[28]:


import math

day = (date.today() - timedelta(days = 1)).strftime('%Y-%m-%d')
#day = '2011-05-01'

dataCompany = json.loads(r.get('dataCompany')) 

tempkWhFridge=0
tempkWhDryer=0
tempkWhMicrowave=0
tempkWhDishwasher=0
tempkWhElectricoven=0
tempkWhHob=0
tempkWhHeater=0

tempCostFridge=0
tempCostDryer=0
tempCostMicrowave=0
tempCostDishwasher=0
tempCostElectricoven=0
tempCostHob=0
tempCostHeater=0

typePrediction='CO'


if predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index.size > 0:
    if appliances['Refrigerator']==True:
        indexI=0
        for predictionTempFridge in predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].Fridge:
            print(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI])
            print(getCostkWh(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI]))
            tempkWhFridge += ((predictionTempFridge/1000)*0.01666666667)
            tempCostFridge += (((predictionTempFridge/1000)*0.01666666667)*getCostkWh(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI]))
            indexI=indexI+1
    if appliances['Dryer']==True:
        indexI=0
        for predictionTempDryer in predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00']["Washer dryer"]:
            tempkWhDryer += ((predictionTempDryer/1000)*0.01666666667)
            tempCostDryer += (((predictionTempDryer/1000)*0.01666666667)*getCostkWh(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI]))
            indexI=indexI+1
    if appliances['Microwave']==True:
        indexI=0
        for predictionTempMicrowave in predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].Microwave:
            tempkWhMicrowave += ((predictionTempMicrowave/1000)*0.01666666667)
            tempCostMicrowave += (((predictionTempMicrowave/1000)*0.01666666667)*getCostkWh(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI]))
            indexI=indexI+1
    if appliances['Dishwasher']==True:
        indexI=0
        for predictionTempDishwasher in predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00']["Dish washer"]:
            tempkWhDishwasher += ((predictionTempDishwasher/1000)*0.01666666667)
            tempCostDishwasher += (((predictionTempDishwasher/1000)*0.01666666667)*getCostkWh(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI]))
            indexI=indexI+1
    if appliances['Oven']==True:
        indexI=0
        for predictionTempElectricoven in predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00']["Electric oven"]:
            tempkWhElectricoven += ((predictionTempElectricoven/1000)*0.01666666667)
            tempCostElectricoven += (((predictionTempElectricoven/1000)*0.01666666667)*getCostkWh(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI]))
            indexI=indexI+1
    if appliances['Electric Hob']==True:
        indexI=0
        for predictionTempHob in predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00']["Electric stove"]:
            tempkWhHob += ((predictionTempHob/1000)*0.01666666667)
            tempCostHob += (((predictionTempHob/1000)*0.01666666667)*getCostkWh(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI]))
            indexI=indexI+1
    if appliances['Electric heater']==True:
        indexI=0
        for predictionTempHeater in predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00']["Electric space heater"]:
            tempkWhHeater += ((predictionTempHeater/1000)*0.01666666667)
            tempCostHeater += (((predictionTempHeater/1000)*0.01666666667)*getCostkWh(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].index[indexI]))
            indexI=indexI+1

    data_setAppliances = []
    max_kWAll = tempkWhFridge+tempkWhDryer+tempkWhMicrowave+tempkWhDishwasher+tempkWhElectricoven+tempkWhHob+tempkWhHeater
    #cost_kWh = float(dataCompany['pricekWh'])

    if appliances['Refrigerator']==True:
        data_setAppliances.append({"kWh":round(tempkWhFridge,3),"appliance":"Refrigerator","percent":math.ceil(((tempkWhFridge/max_kWAll)*100)),"cost":round(tempCostFridge,3)})
    if appliances['Dryer']==True:
        data_setAppliances.append({"kWh":round(tempkWhDryer,3),"appliance":"Dryer","percent":math.ceil(((tempkWhDryer/max_kWAll)*100)),"cost":round(tempCostDryer,3)})
    if appliances['Microwave']==True:
        data_setAppliances.append({"kWh":round(tempkWhMicrowave,3),"appliance":"Microwave","percent":math.ceil(((tempkWhMicrowave/max_kWAll)*100)),"cost":round(tempCostMicrowave,3)})
    if appliances['Dishwasher']==True:
        data_setAppliances.append({"kWh":round(tempkWhDishwasher,3),"appliance":"Dishwasher","percent":math.ceil(((tempkWhDishwasher/max_kWAll)*100)),"cost":round(tempCostDishwasher,3)})
    if appliances['Oven']==True:
        data_setAppliances.append({"kWh":round(tempkWhElectricoven,3),"appliance":"Oven","percent":math.ceil(((tempkWhElectricoven/max_kWAll)*100)),"cost":round(tempCostElectricoven,3)})
    if appliances['Electric Hob']==True:
        data_setAppliances.append({"kWh":round(tempkWhHob,3),"appliance":"Electric Hob","percent":math.ceil(((tempkWhHob/max_kWAll)*100)),"cost":round(tempCostHob,3)})
    if appliances['Electric heater']==True:
        data_setAppliances.append({"kWh":round(tempkWhHeater,3),"appliance":"Electric heater","percent":math.ceil(((tempkWhHeater/max_kWAll)*100)),"cost":round(tempCostHeater,3)})

    json_dumpAppliances = json.dumps(data_setAppliances)
    r.set('consumptionAppliances',json_dumpAppliances) 


# In[29]:


#print(predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'])

resultPredictionsAppliances = predictions[typePrediction].loc[day+' 00:00:00':day+' 23:59:00'].to_json(orient="index")
parsed = json.loads(resultPredictionsAppliances)
jsonOBJ_AppliancesToday = json.dumps(parsed, indent=1)
r.set('consumptionAppliancesToday',jsonOBJ_AppliancesToday)

#print(jsonOBJ_AppliancesToday)



# In[30]:


monthT = date.today().strftime('%m')
monthY = (date.today() - timedelta(days = 1)).strftime('%m') 

kWhMonthRefrigerator=0
kWhMonthDryer=0
kWhMonthMicrowave=0
kWhMonthDishwasher=0
kWhMonthOven=0
kWhMonthElectricHob=0
kWhMonthElectricheater=0
kWhTotalMonth=0

if monthT==monthY:
    consumptionAppliancesMonth = json.loads(r.get('consumptionAppliancesMonth')) 
    
    for key in consumptionAppliancesMonth:
        if appliances['Refrigerator']==True and key['appliance']=='Refrigerator':
            key['kWh']+=round(tempkWhFridge,3)
            kWhTotalMonth+=key['kWh']
            kWhMonthRefrigerator=key['kWh']
            key['cost']+=round(tempCostFridge,3)
            
        if appliances['Dryer']==True and key['appliance']=='Dryer':
            key['kWh']+=round(tempkWhDryer,3)
            kWhTotalMonth+=key['kWh']
            kWhMonthDryer=key['kWh']
            key['cost']+=round(tempCostDryer,3)
            
        if appliances['Microwave']==True and key['appliance']=='Microwave':
            key['kWh']+=round(tempkWhMicrowave,3)
            kWhTotalMonth+=key['kWh']
            kWhMonthMicrowave=key['kWh']
            key['cost']+=round(tempCostMicrowave,3)
            
        if appliances['Dishwasher']==True and key['appliance']=='Dishwasher':
            key['kWh']+=round(tempkWhDishwasher,3)
            kWhTotalMonth+=key['kWh']
            kWhMonthDishwasher=key['kWh']
            key['cost']+=round(tempCostDishwasher,3)
            
        if appliances['Oven']==True and key['appliance']=='Oven':
            key['kWh']+=round(tempkWhElectricoven,3)
            kWhTotalMonth+=key['kWh']
            kWhMonthOven=key['kWh']
            key['cost']+=round(tempCostElectricoven,3)
        
        if appliances['Electric Hob']==True and key['appliance']=='Electric Hob':
            key['kWh']+=round(tempkWhHob,3)
            kWhTotalMonth+=key['kWh']
            kWhMonthElectricHob=key['kWh']
            key['cost']+=round(tempCostHob,3)
    
        if appliances['Electric heater']==True and key['appliance']=='Electric heater':
            key['kWh']+=round(tempkWhHeater,3)
            kWhTotalMonth+=key['kWh']
            kWhMonthElectricheater=key['kWh']
            key['cost']+=round(tempCostHeater,3)

    for key in consumptionAppliancesMonth:
        if appliances['Refrigerator']==True and key['appliance']=='Refrigerator':
            key['percent']=math.ceil(((kWhMonthRefrigerator/kWhTotalMonth)*100))
            
        if appliances['Dryer']==True and key['appliance']=='Dryer':
            key['percent']=math.ceil(((kWhMonthDryer/kWhTotalMonth)*100))
            
        if appliances['Microwave']==True and key['appliance']=='Microwave':
            key['percent']=math.ceil(((kWhMonthMicrowave/kWhTotalMonth)*100))
            
        if appliances['Dishwasher']==True and key['appliance']=='Dishwasher':
            key['percent']=math.ceil(((kWhMonthDishwasher/kWhTotalMonth)*100))
            
        if appliances['Oven']==True and key['appliance']=='Oven':
            key['percent']=math.ceil(((kWhMonthOven/kWhTotalMonth)*100))
        
        if appliances['Electric Hob']==True and key['appliance']=='Electric Hob':
            key['percent']=math.ceil(((kWhMonthElectricHob/kWhTotalMonth)*100))
    
        if appliances['Electric heater']==True and key['appliance']=='Electric heater':
            key['percent']=math.ceil(((kWhMonthElectricheater/kWhTotalMonth)*100))
            
    json_dumpAppliancesMonth2 = json.dumps(consumptionAppliancesMonth)
    r.set('consumptionAppliancesMonth',json_dumpAppliancesMonth2)
    
else:
    data_setAppliancesMonth = []
    if appliances['Refrigerator']==True:
        data_setAppliancesMonth.append({"kWh":round(tempkWhFridge,3),"appliance":"Refrigerator","percent":math.ceil(((tempkWhFridge/max_kWAll)*100)),"cost":round(tempCostFridge,3)})
    if appliances['Dryer']==True:
        data_setAppliancesMonth.append({"kWh":round(tempkWhDryer,3),"appliance":"Dryer","percent":math.ceil(((tempkWhDryer/max_kWAll)*100)),"cost":round(tempCostDryer,3)})
    if appliances['Microwave']==True:
        data_setAppliancesMonth.append({"kWh":round(tempkWhMicrowave,3),"appliance":"Microwave","percent":math.ceil(((tempkWhMicrowave/max_kWAll)*100)),"cost":round(tempCostMicrowave,3)})
    if appliances['Dishwasher']==True:
        data_setAppliancesMonth.append({"kWh":round(tempkWhDishwasher,3),"appliance":"Dishwasher","percent":math.ceil(((tempkWhDishwasher/max_kWAll)*100)),"cost":round(tempCostDishwasher,3)})
    if appliances['Oven']==True:
        data_setAppliancesMonth.append({"kWh":round(tempkWhElectricoven,3),"appliance":"Oven","percent":math.ceil(((tempkWhElectricoven/max_kWAll)*100)),"cost":round(tempCostElectricoven,3)})
    if appliances['Electric Hob']==True:
        data_setAppliancesMonth.append({"kWh":round(tempkWhHob,3),"appliance":"Electric Hob","percent":math.ceil(((tempkWhHob/max_kWAll)*100)),"cost":round(tempCostHob,3)})
    if appliances['Electric heater']==True:
        data_setAppliancesMonth.append({"kWh":round(tempkWhHeater,3),"appliance":"Electric heater","percent":math.ceil(((tempkWhHeater/max_kWAll)*100)),"cost":round(tempCostHeater,3)})

    json_dumpAppliancesMonth = json.dumps(data_setAppliancesMonth)
    r.set('consumptionAppliancesMonth',json_dumpAppliancesMonth) 
    


# In[31]:


yearT = date.today().strftime('%Y')
yearY = (date.today() - timedelta(days = 1)).strftime('%Y') 

kWhYearRefrigerator=0
kWhYearDryer=0
kWhYearMicrowave=0
kWhYearDishwasher=0
kWhYearOven=0
kWhYearElectricHob=0
kWhYearElectricheater=0
kWhTotalYear=0

if yearT==yearY:
    consumptionAppliancesYear = json.loads(r.get('consumptionAppliancesYear')) 
    
    for key in consumptionAppliancesYear:
        if appliances['Refrigerator']==True and key['appliance']=='Refrigerator':
            key['kWh']+=round(tempkWhFridge,3)
            kWhTotalYear+=key['kWh']
            kWhYearRefrigerator=key['kWh']
            key['cost']+=round(tempCostFridge,3)
            
        if appliances['Dryer']==True and key['appliance']=='Dryer':
            key['kWh']+=round(tempkWhDryer,3)
            kWhTotalYear+=key['kWh']
            kWhYearDryer=key['kWh']
            key['cost']+=round(tempCostDryer,3)
            
        if appliances['Microwave']==True and key['appliance']=='Microwave':
            key['kWh']+=round(tempkWhMicrowave,3)
            kWhTotalYear+=key['kWh']
            kWhYearMicrowave=key['kWh']
            key['cost']+=round(tempCostMicrowave,3)
            
        if appliances['Dishwasher']==True and key['appliance']=='Dishwasher':
            key['kWh']+=round(tempkWhDishwasher,3)
            kWhTotalYear+=key['kWh']
            kWhYearDishwasher=key['kWh']
            key['cost']+=round(tempCostDishwasher,3)
            
        if appliances['Oven']==True and key['appliance']=='Oven':
            key['kWh']+=round(tempkWhElectricoven,3)
            kWhTotalYear+=key['kWh']
            kWhYearOven=key['kWh']
            key['cost']+=round(tempCostElectricoven,3)
        
        if appliances['Electric Hob']==True and key['appliance']=='Electric Hob':
            key['kWh']+=round(tempkWhHob,3)
            kWhTotalYear+=key['kWh']
            kWhYearElectricHob=key['kWh']
            key['cost']+=round(tempCostHob,3)
    
        if appliances['Electric heater']==True and key['appliance']=='Electric heater':
            key['kWh']+=round(tempkWhHeater,3)
            kWhTotalYear+=key['kWh']
            kWhYearElectricheater=key['kWh']
            key['cost']+=round(tempCostHeater,3)

    for key in consumptionAppliancesYear:
        if appliances['Refrigerator']==True and key['appliance']=='Refrigerator':
            key['percent']=math.ceil(((kWhYearRefrigerator/kWhTotalYear)*100))
            
        if appliances['Dryer']==True and key['appliance']=='Dryer':
            key['percent']=math.ceil(((kWhYearDryer/kWhTotalYear)*100))
            
        if appliances['Microwave']==True and key['appliance']=='Microwave':
            key['percent']=math.ceil(((kWhYearMicrowave/kWhTotalYear)*100))
            
        if appliances['Dishwasher']==True and key['appliance']=='Dishwasher':
            key['percent']=math.ceil(((kWhYearDishwasher/kWhTotalYear)*100))
            
        if appliances['Oven']==True and key['appliance']=='Oven':
            key['percent']=math.ceil(((kWhYearOven/kWhTotalYear)*100))
        
        if appliances['Electric Hob']==True and key['appliance']=='Electric Hob':
            key['percent']=math.ceil(((kWhYearElectricHob/kWhTotalYear)*100))
    
        if appliances['Electric heater']==True and key['appliance']=='Electric heater':
            key['percent']=math.ceil(((kWhYearElectricheater/kWhTotalYear)*100))
            
    json_dumpAppliancesYear2 = json.dumps(consumptionAppliancesYear)
    r.set('consumptionAppliancesYear',json_dumpAppliancesYear2)
    
else:
    data_setAppliancesYear = []
    if appliances['Refrigerator']==True:
        data_setAppliancesYear.append({"kWh":round(tempkWhFridge,3),"appliance":"Refrigerator","percent":math.ceil(((tempkWhFridge/max_kWAll)*100)),"cost":round(tempCostFridge,3)})
    if appliances['Dryer']==True:
        data_setAppliancesYear.append({"kWh":round(tempkWhDryer,3),"appliance":"Dryer","percent":math.ceil(((tempkWhDryer/max_kWAll)*100)),"cost":round(tempCostDryer,3)})
    if appliances['Microwave']==True:
        data_setAppliancesYear.append({"kWh":round(tempkWhMicrowave,3),"appliance":"Microwave","percent":math.ceil(((tempkWhMicrowave/max_kWAll)*100)),"cost":round(tempCostMicrowave,3)})
    if appliances['Dishwasher']==True:
        data_setAppliancesYear.append({"kWh":round(tempkWhDishwasher,3),"appliance":"Dishwasher","percent":math.ceil(((tempkWhDishwasher/max_kWAll)*100)),"cost":round(tempCostDishwasher,3)})
    if appliances['Oven']==True:
        data_setAppliancesYear.append({"kWh":round(tempkWhElectricoven,3),"appliance":"Oven","percent":math.ceil(((tempkWhElectricoven/max_kWAll)*100)),"cost":round(tempCostElectricoven,3)})
    if appliances['Electric Hob']==True:
        data_setAppliancesYear.append({"kWh":round(tempkWhHob,3),"appliance":"Electric Hob","percent":math.ceil(((tempkWhHob/max_kWAll)*100)),"cost":round(tempCostHob,3)})
    if appliances['Electric heater']==True:
        data_setAppliancesYear.append({"kWh":round(tempkWhHeater,3),"appliance":"Electric heater","percent":math.ceil(((tempkWhHeater/max_kWAll)*100)),"cost":round(tempCostHeater,3)})

    json_dumpAppliancesYear = json.dumps(data_setAppliancesYear)
    r.set('consumptionAppliancesYear',json_dumpAppliancesYear) 


# In[32]:


rtListLen = r.llen("rtList")
#print(rtListLen)
if rtListLen >= 172800:
    r.ltrim("rtList", 0, 86400)

genTodayLen = r.llen("generationTodayList")
#print(genTodayLen)
if genTodayLen >= 48:
    r.ltrim("generationTodayList", 0, 24)


# In[ ]:





# In[ ]:




